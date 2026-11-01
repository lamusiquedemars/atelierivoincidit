<?php

namespace Tests\Feature;

use App\Models\CremonaDelivery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Maracuja\SiteCore\Contact\Filament\Resources\Inquiries\Pages\ViewInquiry;
use Maracuja\SiteCore\Contact\Models\Inquiry;
use Tests\TestCase;

class ContactCoreMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_migration_is_repeatable_and_preserves_delivery_history(): void
    {
        $id = DB::table('contact_submissions')->insertGetId([
            'name' => 'Ancien nom', 'email' => 'old@example.test', 'phone' => '123',
            'subject' => 'Sujet', 'message' => 'Message', 'created_at' => '2024-01-02 03:04:05',
            'updated_at' => '2024-01-03 03:04:05', 'read_at' => '2024-01-04 03:04:05',
            'handled_at' => '2024-01-05 03:04:05',
        ]);
        $delivery = CremonaDelivery::create(['contact_submission_id' => $id, 'idempotency_key' => 'historical-key', 'payload' => ['request' => 'original'], 'status' => 'sent', 'attempts' => 3, 'last_error' => 'historical error', 'sent_at' => '2024-01-06 03:04:05']);
        Http::fake();
        $migration = require database_path('migrations/2026_11_01_120000_migrate_legacy_contact_to_inquiries.php');
        $migration->up();
        $migration->up();
        $this->assertDatabaseCount('inquiries', 1);
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseHas('inquiries', ['name' => 'Ancien nom', 'email' => 'old@example.test', 'phone' => '123', 'subject' => 'Sujet', 'message' => 'Message', 'status' => 'handled', 'created_at' => '2024-01-02 03:04:05', 'read_at' => '2024-01-04 03:04:05', 'handled_at' => '2024-01-05 03:04:05']);
        $delivery->refresh();
        $this->assertNotNull($delivery->inquiry_id);
        $this->assertSame('sent', $delivery->status);
        $this->assertSame('historical-key', $delivery->idempotency_key);
        $this->assertSame(3, $delivery->attempts);
        $this->assertSame(['request' => 'original'], $delivery->payload);
        Http::assertNothingSent();
    }

    public function test_cremona_failure_does_not_block_the_public_form(): void
    {
        config()->set('maracuja-cremona-connector.enabled', true);
        config()->set('maracuja-cremona-connector.endpoint', 'https://cremona.test/api/v1/incoming-requests');
        config()->set('maracuja-cremona-connector.token', 'test-token');
        config()->set('maracuja-cremona-connector.site_reference', 'atelierivoincidit.fr');
        Http::fake(['cremona.test/*' => Http::response([], 503)]);
        $this->post('/contact', ['email' => 'visitor@example.test', 'message' => 'Bonjour'])->assertRedirect('/contact');
        $this->assertDatabaseHas('inquiries', ['email' => 'visitor@example.test']);
        $this->assertDatabaseHas('cremona_deliveries', ['status' => 'pending', 'response_status' => 503]);
    }

    public function test_admin_can_open_the_inquiry_and_update_status_and_notes(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_admin' => true]);
        $inquiry = Inquiry::create(['name' => 'Test', 'email' => 'test@example.test', 'message' => 'Bonjour', 'created_at' => '2025-02-03 12:00:00']);
        $this->actingAs($admin)->get('/admin/inquiries')->assertOk()->assertSee('Reçu le')->assertSee('Test');
        $this->get('/admin/inquiries/'.$inquiry->id)->assertOk()->assertSee('Bonjour');
        Livewire::test(ViewInquiry::class, ['record' => $inquiry->id])
            ->callAction('changeStatus', ['status' => 'handled'])->assertHasNoActionErrors()
            ->callAction('addNote', ['note' => 'Suivi effectué'])->assertHasNoActionErrors();
        $this->assertDatabaseHas('inquiries', ['id' => $inquiry->id, 'status' => 'handled', 'internal_notes' => 'Suivi effectué']);
    }
}
