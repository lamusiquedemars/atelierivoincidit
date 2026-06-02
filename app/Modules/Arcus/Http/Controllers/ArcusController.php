<?php

namespace App\Modules\Arcus\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Arcus\Support\ArcusCatalog;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArcusController extends Controller
{
    public function index(): View
    {
        return view('site.arcus.index', [
            'settings' => SiteSetting::current(),
            'series' => ArcusCatalog::seriesCards(),
        ]);
    }

    public function range(Request $request, string $range): View
    {
        $content = ArcusCatalog::range($range);
        abort_if($content === null, 404);

        return view('site.arcus.range', [
            'settings' => SiteSetting::current(),
            'rangeSlug' => $range,
            'content' => $content,
            'bows' => ArcusCatalog::bowsByRange($range, $request->query('instrument')),
        ]);
    }

    public function show(string $code): View
    {
        $bow = ArcusCatalog::bowByCode($code);
        abort_if($bow === null, 404);

        return view('site.arcus.show', [
            'settings' => SiteSetting::current(),
            'bow' => $bow,
            'photos' => ArcusCatalog::galleryImages($bow['code']),
            'priceData' => ArcusCatalog::priceData($bow),
            'statusLabel' => ArcusCatalog::statusLabel($bow['status'] ?? null),
        ]);
    }
}
