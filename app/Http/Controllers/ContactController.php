<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionConfirmation;
use App\Mail\ContactSubmissionReceived;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Maracuja\SiteCore\Contact\Actions\StoreInquiry;
use Maracuja\SiteCore\Contact\Data\ContactRequestData;
use Maracuja\SiteCore\CremonaConnector\Services\CremonaLeadReceiver;

class ContactController extends Controller
{
    public function create(): View
    {
        abort_unless(Modules::enabled('contact'), 404);

        return view('site.contact', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Modules::enabled('contact'), 404);

        $settings = SiteSetting::current();

        $rules = [
            'website' => ['nullable', 'string', 'max:0'],
            'email' => ['required', 'string', 'max:160', 'email:rfc', 'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'],
            'message' => ['required', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:60'],
            'subject' => ['nullable', 'string', 'max:160'],
        ];

        if ($settings->contact_form_show_name) {
            $rules['name'] = ['required', 'string', 'max:120'];
        } else {
            $rules['name'] = ['sometimes', 'nullable', 'string', 'max:120'];
        }

        $data = $request->validate($rules);

        if (! $settings->contact_form_show_name) {
            $data['name'] = $data['email'];
        }

        $contactRequest = ContactRequestData::fromArray([
            ...$data,
            'metadata' => [
                'attribution' => $this->attribution($request),
            ],
        ]);
        $submission = StoreInquiry::run($contactRequest);

        try {
            app(CremonaLeadReceiver::class)->dispatch($submission, $contactRequest);
        } catch (\Throwable $exception) {
            report($exception);
        }

        if ($settings->contact_form_send_admin_email && $settings->contact_email) {
            try {
                Mail::to($settings->contact_email)->send(new ContactSubmissionReceived($submission));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        if ($settings->contact_form_send_confirmation_email) {
            try {
                Mail::to($submission->email)->send(new ContactSubmissionConfirmation($submission));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect()
            ->route('contact')
            ->with('contact_submission_success', true)
            ->with('status', 'Votre message a bien été enregistré. Nous le traitons depuis l’administration.');
    }

    /** @return array<string, string>|array{} */
    private function attribution(Request $request): array
    {
        $touch = json_decode((string) $request->cookie('ivo_attribution_last'), true);

        if (! is_array($touch)) {
            return [];
        }

        return collect($touch)
            ->only(['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gbraid', 'wbraid', 'landing_page', 'referrer', 'captured_at'])
            ->filter(fn (mixed $value): bool => is_string($value) && $value !== '')
            ->map(fn (string $value): string => mb_substr($value, 0, 2048))
            ->all();
    }
}
