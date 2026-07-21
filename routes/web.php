<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AtelierPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicStorageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Modules\Arcus\Http\Controllers\ArcusController;
use App\Support\Modules;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Fallback for hosts such as LWS that expose the project root instead of public/.
Route::get('/storage/{path}', PublicStorageController::class)
    ->where('path', '.*')
    ->name('public-storage');

Route::get('/', HomeController::class)->name('home');

if (config('maracuja.theme') === 'atelier') {
    Route::get('/archetier', [AtelierPageController::class, 'officina'])->name('atelier.officina');
    Route::get('/essai', [AtelierPageController::class, 'probatio'])->name('atelier.probatio');
    Route::get('/mentions-legales', [AtelierPageController::class, 'legal'])->name('atelier.legal');
    Route::get('/cgv', [AtelierPageController::class, 'terms'])->name('atelier.terms');
}

Route::get('/actualites', [NewsController::class, 'index'])->name('news.index');
Route::get('/actualites/{slug}', [NewsController::class, 'show'])->name('news.show');

if (Modules::enabled('articles')) {
    Route::get('/article.php', [ArticleController::class, 'legacy'])->name('articles.legacy');
    Route::get('/'.config('maracuja.articles.public_path', 'articles'), [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/'.config('maracuja.articles.public_path', 'articles').'/{slug}', [ArticleController::class, 'show'])->name('articles.show');
}

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

if (Modules::enabled('arcus')) {
    Route::get('/arcus', [ArcusController::class, 'index'])->name('arcus.index');
    Route::get('/arcus/{range}', [ArcusController::class, 'range'])
        ->whereIn('range', ['ars-antiqua', 'ars-classica', 'ars-nova'])
        ->name('arcus.range');
    Route::get('/arcus/{code}', [ArcusController::class, 'show'])->name('arcus.show');
}

Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
