<?php

namespace App\Http\Controllers;

use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\AtelierHomeContent;
use App\Support\Modules;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $settings = SiteSetting::current();
        $homePage = Modules::enabled('pages')
            ? Page::query()->where('slug', 'accueil')->where('is_published', true)->first()
            : null;

        return view('site.home', [
            'settings' => $settings,
            'homePage' => $homePage,
            'homeNotice' => Modules::enabled('notices')
                ? SiteNotice::query()->visible()->forPlacement('home')->latest('starts_at')->first()
                : null,
            'newsPosts' => Modules::enabled('news')
                ? NewsPost::query()->forListing()->limit(3)->get()
                : collect(),
            'galleryImages' => Modules::enabled('gallery')
                ? GalleryImage::query()->where('is_published', true)->orderBy('position')->limit(6)->get()
                : collect(),
            'atelierShowcase' => config('maracuja.theme') === 'atelier' ? AtelierHomeContent::showcase() : [],
            'atelierQuotes' => config('maracuja.theme') === 'atelier' ? AtelierHomeContent::quotes() : [],
        ]);
    }
}
