<?php

namespace App\Jobs;

use App\Models\CremonaDelivery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendCremonaDelivery implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public array $backoff = [60, 300, 900, 3600];

    public function __construct(public int $deliveryId) {}

    public function handle(): void
    {
        $d = CremonaDelivery::query()->findOrFail($this->deliveryId);
        if ($d->status === 'sent') {
            return;

        }

        $d->update([
            'status' => 'sending',
            'attempts' => $d->attempts + 1,
            'last_attempt_at' => now(),
        ]);

        try {
            $r = Http::acceptJson()
                ->withToken((string) config('services.cremona.incoming_requests_token'))
                ->withHeader('Idempotency-Key', $d->idempotency_key)
                ->timeout(15)
                ->post((string) config('services.cremona.incoming_requests_url'), $d->payload);
        } catch (\Throwable $exception) {
            $d->update([
                'status' => 'pending',
                'last_error' => $exception->getMessage(),
            ]);

            return;
        }

        if (in_array($r->status(), [401, 403, 409, 422], true)) {
            $d->update([
                'status' => 'failed',
                'response_status' => $r->status(),
                'last_error' => "Cremona returned HTTP {$r->status()}.",
            ]);

            return;
        }

        if (! $r->successful()) {
            $d->update([
                'status' => 'pending',
                'response_status' => $r->status(),
                'last_error' => "Cremona returned HTTP {$r->status()}.",
            ]);

            return;
        }

        $d->update([
            'status' => 'sent',
            'response_status' => $r->status(),
            'sent_at' => now(),
            'last_error' => null,
        ]);
    }
}
