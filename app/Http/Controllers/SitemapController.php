<?php

namespace App\Http\Controllers;

use App\Modules\Arcus\Models\Bow;
use App\Modules\Arcus\Support\ArcusCatalog;
use App\Modules\Articles\Models\Article;
use App\Modules\News\Models\NewsPost;
use App\Modules\Pages\Models\Page;
use App\Support\Modules;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];

        $home = Modules::enabled('pages')
            ? Page::query()->where('slug', 'accueil')->where('is_published', true)->first()
            : null;

        $this->addUrl($urls, route('home'), $home?->updated_at, '1.0');

        if (config('maracuja.theme') === 'atelier') {
            $this->addUrl($urls, route('atelier.probatio'), priority: '0.8');
            $this->addUrl($urls, route('atelier.officina'), priority: '0.8');
            $this->addUrl($urls, route('atelier.legal'), priority: '0.3');
            $this->addUrl($urls, route('atelier.terms'), priority: '0.3');
        }

        if (Modules::enabled('contact')) {
            $this->addUrl($urls, route('contact'), priority: '0.7');
        }

        if (Modules::enabled('arcus')) {
            $this->addUrl($urls, route('arcus.index'), priority: '0.9');

            foreach (array_keys(ArcusCatalog::ranges()) as $range) {
                $this->addUrl($urls, route('arcus.range', $range), priority: '0.8');
            }

            Bow::query()
                ->where('active', true)
                ->orderBy('code')
                ->get(['code'])
                ->each(function (Bow $bow) use (&$urls): void {
                    $this->addUrl(
                        $urls,
                        route('arcus.show', strtolower($bow->code)),
                        priority: '0.7',
                    );
                });
        }

        if (Modules::enabled('articles')) {
            $this->addUrl($urls, route('articles.index'), priority: '0.7');

            Article::query()
                ->visible()
                ->orderBy('slug')
                ->get(['slug', 'updated_at'])
                ->each(function (Article $article) use (&$urls): void {
                    $this->addUrl(
                        $urls,
                        route('articles.show', $article->slug),
                        $article->updated_at,
                        '0.6',
                    );
                });
        }

        if (Modules::enabled('pages')) {
            Page::query()
                ->where('is_published', true)
                ->get(['slug', 'updated_at'])
                ->each(function (Page $page) use (&$urls): void {
                    if (in_array($page->slug, ['accueil', 'actualites', 'contact'], true)) {
                        return;
                    }

                    if ($page->isModule()) {
                        return;
                    }

                    $url = $page->publicUrl();

                    if (! $url) {
                        return;
                    }

                    $this->addUrl($urls, $url, $page->updated_at, '0.8');
                });
        }

        if (Modules::enabled('news')) {
            $this->addUrl($urls, route('news.index'), priority: '0.6');

            NewsPost::query()
                ->visible()
                ->where('has_detail_page', true)
                ->whereNotNull('content')
                ->where('content', '!=', '')
                ->orderBy('slug')
                ->get(['slug', 'updated_at'])
                ->each(function (NewsPost $post) use (&$urls): void {
                    $this->addUrl($urls, route('news.show', $post->slug), $post->updated_at, '0.5');
                });
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @param  array<string, array{loc: string, lastmod: ?Carbon, priority: string}>  $urls
     */
    private function addUrl(
        array &$urls,
        string $location,
        ?Carbon $lastModified = null,
        string $priority = '0.5',
    ): void {
        $urls[$location] = [
            'loc' => $location,
            'lastmod' => $lastModified,
            'priority' => $priority,
        ];
    }
}
