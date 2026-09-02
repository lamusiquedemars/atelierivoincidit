<?php

namespace Tests\Feature;

use App\Modules\Arcus\Models\Bow;
use App\Modules\Articles\Models\Article;
use App\Modules\News\Models\NewsPost;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_page_outputs_core_seo_tags(): void
    {
        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'default_seo_title' => 'Maracuja default',
            'default_seo_description' => 'Description par défaut du starter.',
            'default_og_image_path' => '/demo/theme-system.svg',
        ]);

        Page::query()->create([
            'title' => 'Politique confidentialité',
            'slug' => 'politique-confidentialite',
            'type' => Page::TYPE_TEXT,
            'seo_title' => 'Politique confidentialité SEO',
            'seo_description' => 'Une description SEO claire.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/politique-confidentialite')
            ->assertOk()
            ->assertSee('<title>Politique confidentialité SEO</title>', false)
            ->assertSee('<meta name="description" content="Une description SEO claire.">', false)
            ->assertSee('<link rel="canonical" href="'.url('/politique-confidentialite').'">', false)
            ->assertSee('<meta property="og:title" content="Politique confidentialité SEO">', false)
            ->assertSee('<meta property="og:image" content="'.url('/demo/theme-system.svg').'">', false)
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_robots_blocks_indexing_by_default(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /');
    }

    public function test_atelier_pages_expose_clear_titles_and_structured_entities(): void
    {
        config([
            'maracuja.theme' => 'atelier',
            'maracuja.seo.indexable' => true,
        ]);

        SiteSetting::query()->create([
            'site_name' => 'Atelier Ivo Incidit',
            'contact_email' => 'info@example.test',
        ]);

        $response = $this->get('/archetier');

        $response
            ->assertOk()
            ->assertSee('<title>Ivo Correia de Melo, archetier à Lyon | Ivo Incidit</title>', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('"@type":"LocalBusiness"', false)
            ->assertSee('"@type":"Person"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"addressLocality":"Collonges-au-Mont-d’Or"', false);

        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $response->getContent(), $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $json) {
            json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        }
    }

    public function test_empty_article_index_is_noindex_and_absent_from_sitemap(): void
    {
        config([
            'maracuja.modules.articles' => true,
            'maracuja.seo.indexable' => true,
        ]);

        SiteSetting::current();

        $this->get('/articles')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('<loc>'.url('/articles').'</loc>', false);
    }

    public function test_sitemap_lists_public_content(): void
    {
        config([
            'maracuja.theme' => 'atelier',
            'maracuja.modules.arcus' => true,
            'maracuja.modules.articles' => true,
            'maracuja.modules.news' => true,
            'maracuja.modules.contact_form' => true,
        ]);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'is_published' => true,
            'published_at' => now(),
        ]);

        NewsPost::query()->create([
            'title' => 'Demo',
            'slug' => 'demo',
            'is_published' => true,
            'has_detail_page' => true,
            'content' => 'Contenu public.',
            'published_at' => now(),
        ]);

        Article::query()->create([
            'title' => 'Guide',
            'slug' => 'guide',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Bow::query()->create([
            'code' => 'V001',
            'active' => true,
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('content-type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<loc>'.url('/arcus').'</loc>', false)
            ->assertSee('<loc>'.url('/arcus/ars-antiqua').'</loc>', false)
            ->assertSee('<loc>'.url('/arcus/ars-classica').'</loc>', false)
            ->assertSee('<loc>'.url('/arcus/ars-nova').'</loc>', false)
            ->assertSee('<loc>'.url('/arcus/v001').'</loc>', false)
            ->assertSee('<loc>'.url('/essai').'</loc>', false)
            ->assertSee('<loc>'.url('/archetier').'</loc>', false)
            ->assertSee('<loc>'.url('/articles').'</loc>', false)
            ->assertSee('<loc>'.url('/articles/guide').'</loc>', false)
            ->assertSee('<loc>'.url('/contact').'</loc>', false)
            ->assertSee('<loc>'.url('/services').'</loc>', false)
            ->assertSee('<loc>'.url('/actualites/demo').'</loc>', false);
    }

    public function test_sitemap_excludes_non_public_dynamic_content(): void
    {
        config([
            'maracuja.modules.arcus' => true,
            'maracuja.modules.articles' => true,
            'maracuja.modules.news' => true,
        ]);

        Article::query()->create([
            'title' => 'Brouillon',
            'slug' => 'brouillon',
            'is_published' => false,
        ]);

        NewsPost::query()->create([
            'title' => 'Sans détail',
            'slug' => 'sans-detail',
            'is_published' => true,
            'has_detail_page' => false,
            'published_at' => now(),
        ]);

        Bow::query()->create([
            'code' => 'V002',
            'active' => false,
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee(url('/articles/brouillon'), false)
            ->assertDontSee(url('/actualites/sans-detail'), false)
            ->assertDontSee(url('/arcus/v002'), false)
            ->assertDontSee(url('/admin'), false)
            ->assertDontSee(url('/article.php'), false);
    }
}
