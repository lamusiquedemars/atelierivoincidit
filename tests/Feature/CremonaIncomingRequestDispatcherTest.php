<?php

namespace Tests\Feature;

use App\Modules\Contact\Models\ContactSubmission;
use App\Services\CremonaIncomingRequestDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CremonaIncomingRequestDispatcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_contact_and_first_party_attribution_to_cremona(): void
    {
        config()->set('services.cremona.incoming_requests_url', 'https://cremona.test/api/v1/incoming-requests');
        config()->set('services.cremona.incoming_requests_token', 'secret-token');
        Http::fake(['cremona.test/*' => Http::response([], 201)]);
        $submission = ContactSubmission::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Je souhaite essayer un archet.',
        ]);
        $touch = json_encode(['utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'atelier-archets', 'gclid' => 'click-1', 'landing_page' => '/contact', 'captured_at' => '2026-08-27T10:00:00Z']);
        $request = Request::create('/contact', 'POST', [], ['ivo_attribution_first' => $touch, 'ivo_attribution_last' => $touch]);

        app(CremonaIncomingRequestDispatcher::class)->dispatch($submission, $request);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://cremona.test/api/v1/incoming-requests'
                && $request->hasHeader('Authorization', 'Bearer secret-token')
                && $request->hasHeader('Idempotency-Key', 'atelierivoincidit:contact:1')
                && $request['attribution']['campaign'] === 'atelier-archets'
                && $request['attribution']['last_touch']['gclid'] === 'click-1';
        });
    }

    public function test_it_is_inactive_without_its_server_configuration(): void
    {
        Http::fake();
        $submission = ContactSubmission::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.',
        ]);

        app(CremonaIncomingRequestDispatcher::class)->dispatch($submission, Request::create('/contact', 'POST'));

        Http::assertNothingSent();
    }
}
