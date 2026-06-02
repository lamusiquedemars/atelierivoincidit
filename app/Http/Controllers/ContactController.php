<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionConfirmation;
use App\Mail\ContactSubmissionReceived;
use App\Modules\Contact\Models\ContactSubmission;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

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

        $submission = ContactSubmission::query()->create($data);

        if ($settings->contact_form_send_admin_email && $settings->contact_email) {
            Mail::to($settings->contact_email)->send(new ContactSubmissionReceived($submission));
        }

        if ($settings->contact_form_send_confirmation_email) {
            Mail::to($submission->email)->send(new ContactSubmissionConfirmation($submission));
        }

        return redirect()
            ->route('contact')
            ->with('status', 'Votre message a bien été enregistré. Nous le traitons depuis l’administration.');
    }
}
