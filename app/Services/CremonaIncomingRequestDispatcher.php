<?php

namespace App\Services;

use App\Modules\Contact\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CremonaIncomingRequestDispatcher
{
    public function dispatch(ContactSubmission $submission, Request $request): void
    {
        $url = config('services.cremona.incoming_requests_url');
        $token = config('services.cremona.incoming_requests_token');

        if (! is_string($url) || $url === '' || ! is_string($token) || $token === '') {
            return;
        }

        try {
            Http::acceptJson()
                ->withToken($token)
                ->withHeaders(['Idempotency-Key' => "atelierivoincidit:contact:{$submission->getKey()}"])
                ->timeout(5)
                ->post($url, $this->payload($submission, $request))
                ->throw();
        } catch (\Throwable $exception) {
            // The local record and email workflow remain authoritative if Cremona is unavailable.
            Log::warning('Cremona contact dispatch failed.', [
                'contact_submission_id' => $submission->getKey(),
                'exception' => $exception::class,
            ]);
        }
    }

    /** @return array<string, mixed> */
    private function payload(ContactSubmission $submission, Request $request): array
    {
        $firstTouch = $this->touch($request->cookie('ivo_attribution_first'));
        $lastTouch = $this->touch($request->cookie('ivo_attribution_last'));

        return [
            'source' => [
                'channel' => 'website',
                'name' => 'maracuja-cms',
                'site_reference' => config('services.cremona.site_reference'),
                'form_reference' => 'contact',
            ],
            'contact' => [
                'name' => $submission->name,
                'email' => $submission->email,
                'phone' => $submission->phone,
            ],
            'attribution' => [
                'source' => $lastTouch['utm_source'] ?? null,
                'medium' => $lastTouch['utm_medium'] ?? null,
                'campaign' => $lastTouch['utm_campaign'] ?? null,
                'first_touch' => $firstTouch,
                'last_touch' => $lastTouch,
                'method' => $lastTouch === null ? null : 'first_party',
                'confidence' => $lastTouch === null ? null : 1,
            ],
            'request' => [
                'subject' => $submission->subject,
                'message' => $submission->message,
            ],
        ];
    }

    /** @return array<string, string>|null */
    private function touch(mixed $value): ?array
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        if (! is_array($decoded)) {
            return null;
        }

        $allowed = Arr::only($decoded, [
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
            'gclid', 'gbraid', 'wbraid', 'landing_page', 'referrer', 'captured_at',
        ]);

        return collect($allowed)
            ->filter(fn (mixed $item): bool => is_string($item) && $item !== '')
            ->map(fn (string $item): string => mb_substr($item, 0, 2048))
            ->all() ?: null;
    }
}
