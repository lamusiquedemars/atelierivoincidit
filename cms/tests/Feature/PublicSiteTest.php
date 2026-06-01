<?php

namespace Tests\Feature;

use App\Mail\ContactSubmissionConfirmation;
use App\Mail\ContactSubmissionReceived;
use App\Modules\Contact\Models\ContactSubmission;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Modules\Articles\Models\Article;
use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function enableNewsModule(): void
    {
        config(['maracuja.modules.news' => true]);
    }

    public function test_home_page_renders_the_public_pitch(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'hero_title' => 'Un site clair',
            'hero_subtitle' => 'Une administration simple',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee(config('maracuja.theme') === 'atelier' ? 'Atelier Ivo Incidit' : 'Un site clair')
            ->assertSee(config('maracuja.theme') === 'atelier' ? 'Ars Antiqua' : 'Essence');
    }

    public function test_services_page_uses_dedicated_demo_template(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'template' => 'services',
            'hero_title' => 'Des sites vitrines administrables',
            'body_blocks' => [
                'essence_price' => 'A partir de 1500',
            ],
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertSee('Trois niveaux')
            ->assertSee('A partir de 1500');
    }

    public function test_services_page_uses_content_slot_for_price(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'template' => 'services',
            'hero_title' => 'Des sites vitrines administrables',
            'is_published' => true,
            'published_at' => now(),
        ]);

        ContentSlot::query()->create([
            'key' => 'services.essence.price',
            'label' => 'Prix Essence',
            'group' => 'Services',
            'type' => 'price',
            'value' => 'A partir de 1800',
            'is_locked' => true,
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertSee('A partir de 1800')
            ->assertDontSee('A partir de 1500');
    }

    public function test_home_gallery_uses_configured_layout(): void
    {
        config(['maracuja.gallery.layout' => 'featured']);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        GalleryImage::query()->create([
            'title' => 'Image demo',
            'caption' => 'Legende demo',
            'image_path' => '/demo/admin-simple.svg',
            'width' => 1200,
            'height' => 800,
            'position' => 1,
            'is_published' => true,
        ]);

        $response = $this->get('/')
            ->assertOk()
            ->assertSee('showcase--featured');

        if (config('maracuja.theme') === 'atelier') {
            $response
                ->assertSee('/assets/images/showcase-hausses.jpeg')
                ->assertDontSee('/demo/admin-simple.svg');
        } else {
            $response->assertSee('/demo/admin-simple.svg');
        }
    }

    public function test_home_renders_active_notice_only(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        SiteNotice::query()->create([
            'title' => 'Horaires d ete',
            'message' => 'Ouverture exceptionnelle sur rendez-vous.',
            'placement' => 'home',
            'tone' => 'warning',
            'is_published' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        SiteNotice::query()->create([
            'title' => 'Ancienne annonce',
            'message' => 'Message expire.',
            'placement' => 'home',
            'tone' => 'info',
            'is_published' => true,
            'starts_at' => now()->subDays(3),
            'ends_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Horaires d ete')
            ->assertSee('Ouverture exceptionnelle')
            ->assertDontSee('Ancienne annonce');
    }

    public function test_home_news_listing_hides_expired_posts(): void
    {
        $this->enableNewsModule();

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        NewsPost::query()->create([
            'title' => 'Actualite active',
            'slug' => 'actualite-active',
            'excerpt' => 'Visible maintenant.',
            'is_published' => true,
            'published_at' => now()->subHour(),
            'expires_at' => now()->addDay(),
        ]);

        NewsPost::query()->create([
            'title' => 'Actualite expiree',
            'slug' => 'actualite-expiree',
            'excerpt' => 'Invisible maintenant.',
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'expires_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Actualite active')
            ->assertDontSee('Actualite expiree');
    }

    public function test_published_page_is_available_by_slug(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Methode',
            'slug' => 'methode',
            'hero_title' => 'Une structure avant les options',
            'body_blocks' => ['section' => 'Admin simple'],
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/methode')
            ->assertOk()
            ->assertSee('Fil d’Ariane')
            ->assertSee('Une structure avant les options')
            ->assertSee('Admin simple')
            ->assertSee('Retour à l’accueil');
    }

    public function test_contact_form_stores_submission_and_sends_mail(): void
    {
        Mail::fake();

        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => 'contact@maracuja.test',
        ]);

        $this->get('/contact')
            ->assertOk()
            ->assertDontSee('name="name"', false)
            ->assertDontSee('name="phone"', false)
            ->assertDontSee('name="subject"', false);

        $this->post('/contact', [
            'email' => 'ivo@example.test',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas(ContactSubmission::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertSent(ContactSubmissionReceived::class);
    }

    public function test_contact_form_rejects_invalid_email_without_top_level_domain(): void
    {
        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => 'contact@maracuja.test',
        ]);

        $this->post('/contact', [
            'email' => 'ivo@mail',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount(ContactSubmission::class, 0);
    }

    public function test_contact_form_stores_submission_when_admin_email_is_not_configured(): void
    {
        Mail::fake();

        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => null,
        ]);

        $this->post('/contact', [
            'email' => 'ivo@example.test',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas(ContactSubmission::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertNothingSent();
    }

    public function test_contact_form_sends_user_confirmation_if_enabled(): void
    {
        Mail::fake();

        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => 'contact@maracuja.test',
            'contact_form_send_confirmation_email' => true,
        ]);

        $this->post('/contact', [
            'email' => 'ivo@example.test',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas(ContactSubmission::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertSent(ContactSubmissionConfirmation::class);
    }

    public function test_disabled_news_module_returns_not_found(): void
    {
        config(['maracuja.modules.news' => false]);

        $this->get('/actualites')->assertNotFound();
    }

    public function test_essence_offer_hides_signature_modules_from_public_navigation(): void
    {
        config(['maracuja.offer' => 'essence']);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('href="http://localhost/actualites"', false)
            ->assertDontSee('Galerie demo');

        $this->get('/actualites')->assertNotFound();
    }

    public function test_expired_news_post_detail_returns_not_found(): void
    {
        $this->enableNewsModule();

        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Actualite expiree',
            'slug' => 'actualite-expiree',
            'excerpt' => 'Invisible maintenant.',
            'content' => '<p>Archive non visible.</p>',
            'is_published' => true,
            'has_detail_page' => true,
            'published_at' => now()->subDays(5),
            'expires_at' => now()->subDay(),
        ]);

        $this->get('/actualites/actualite-expiree')->assertNotFound();
    }

    public function test_news_listing_orders_pinned_posts_first(): void
    {
        $this->enableNewsModule();

        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Actualite recente',
            'slug' => 'actualite-recente',
            'excerpt' => 'Recente mais non epinglee.',
            'is_published' => true,
            'is_pinned' => false,
            'has_detail_page' => true,
            'published_at' => now(),
            'expires_at' => now()->addDay(),
        ]);

        NewsPost::query()->create([
            'title' => 'Actualite epinglee',
            'slug' => 'actualite-epinglee',
            'excerpt' => 'Prioritaire.',
            'is_published' => true,
            'is_pinned' => true,
            'has_detail_page' => true,
            'published_at' => now()->subDays(3),
            'expires_at' => now()->addDay(),
        ]);

        $this->get('/actualites')
            ->assertOk()
            ->assertSeeInOrder(['Actualite epinglee', 'Actualite recente']);
    }

    public function test_news_without_detail_page_has_no_public_detail(): void
    {
        $this->enableNewsModule();

        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Annonce simple',
            'slug' => 'annonce-simple',
            'excerpt' => 'Visible dans le listing uniquement.',
            'content' => '<p>Ne doit pas etre visible en page detail.</p>',
            'is_published' => true,
            'is_pinned' => false,
            'has_detail_page' => false,
            'published_at' => now()->subHour(),
            'expires_at' => now()->addDay(),
        ]);

        $this->get('/actualites')
            ->assertOk()
            ->assertSee('Annonce simple')
            ->assertDontSee('href="http://localhost/actualites/annonce-simple"', false);

        $this->get('/actualites/annonce-simple')->assertNotFound();
    }

    public function test_news_detail_shows_breadcrumb_and_back_link(): void
    {
        $this->enableNewsModule();

        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Actualite active',
            'slug' => 'actualite-active',
            'excerpt' => 'Visible maintenant.',
            'content' => '<p>Detail de l actualite.</p>',
            'is_published' => true,
            'has_detail_page' => true,
            'published_at' => now()->subHour(),
            'expires_at' => now()->addDay(),
        ]);

        $this->get('/actualites/actualite-active')
            ->assertOk()
            ->assertSee('Fil d’Ariane')
            ->assertSee('Actualités')
            ->assertSee('Retour aux actualités');
    }

    public function test_articles_render_structured_blocks(): void
    {
        SiteSetting::current();

        Article::query()->create([
            'title' => 'Bois et geste',
            'slug' => 'bois-et-geste',
            'excerpt' => 'Une note d’atelier.',
            'body_blocks' => [
                [
                    'type' => 'heading',
                    'level' => '2',
                    'heading' => 'Une matière vivante',
                ],
                [
                    'type' => 'rich_text',
                    'text' => '<p>Le bois répond au geste.</p>',
                ],
                [
                    'type' => 'quote',
                    'quote' => 'Le geste confirme.',
                    'author' => 'Atelier',
                ],
                [
                    'type' => 'table',
                    'table_rows' => "Bois | Usage\nCumaru | Archet moderne",
                ],
            ],
            'is_published' => true,
            'published_at' => now()->subHour(),
        ]);

        $this->get('/articles')
            ->assertOk()
            ->assertSee('Bois et geste')
            ->assertSee('Une note d’atelier.');

        $this->get('/articles/bois-et-geste')
            ->assertOk()
            ->assertSee('Une matière vivante')
            ->assertSee('Le bois répond au geste.')
            ->assertSee('Le geste confirme.')
            ->assertSee('Cumaru')
            ->assertSee('Retour aux articles');

        $this->get('/article.php?slug=bois-et-geste')
            ->assertRedirect('/articles/bois-et-geste');
    }
}
