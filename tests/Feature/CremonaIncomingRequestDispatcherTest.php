<?php

namespace Tests\Feature;

use App\Services\CremonaIncomingRequestDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maracuja\SiteCore\Contact\Models\Inquiry;
use Tests\TestCase;

class CremonaIncomingRequestDispatcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_contact_and_first_party_attribution_to_cremona(): void
    {
        config()->set('services.cremona.incoming_requests_url', 'https://cremona.test/api/v1/incoming-requests');
        config()->set('services.cremona.incoming_requests_token', 'secret-token');
        Http::fake(['cremona.test/*' => Http::response([], 201)]);
        $submission = Inquiry::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Je souhaite essayer un archet.',
        ]);
        $touch = json_encode(['utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'atelier-archets', 'gclid' => 'click-1', 'landing_page' => '/contact', 'captured_at' => '2026-08-27T10:00:00Z']);
        $request = Request::create('/contact', 'POST', [], ['ivo_attribution_first' => $touch, 'ivo_attribution_last' => $touch]);

        app(CremonaIncomingRequestDispatcher::class)->dispatch($submission, $request);

        Http::assertSent(function ($request) use ($submission): bool {
            return $request->url() === 'https://cremona.test/api/v1/incoming-requests'
                && $request->hasHeader('Authorization', 'Bearer secret-token')
                && $request->hasHeader('Idempotency-Key', 'atelierivoincidit:inquiry:'.$submission->getKey())
                && $request['attribution']['campaign'] === 'atelier-archets'
                && $request['attribution']['last_touch']['gclid'] === 'click-1';
        });
    }

    public function test_it_is_inactive_without_its_server_configuration(): void
    {
        Http::fake();
        $submission = Inquiry::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.',
        ]);

        app(CremonaIncomingRequestDispatcher::class)->dispatch($submission, Request::create('/contact', 'POST'));

        Http::assertNothingSent();
    }

    public function test_it_records_a_retryable_delivery_when_cremona_is_unavailable(): void
    {
        config()->set('services.cremona.incoming_requests_url', 'https://cremona.test/api/v1/incoming-requests');
        config()->set('services.cremona.incoming_requests_token', 'secret-token');
        Http::fake(['cremona.test/*' => Http::response([], 503)]);
        $submission = Inquiry::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.',
        ]);

        app(CremonaIncomingRequestDispatcher::class)->dispatch($submission, Request::create('/contact', 'POST'));

        $this->assertDatabaseHas('cremona_deliveries', [
            'inquiry_id' => $submission->getKey(),
            'status' => 'pending',
            'response_status' => 503,
            'attempts' => 1,
        ]);
    }
}
