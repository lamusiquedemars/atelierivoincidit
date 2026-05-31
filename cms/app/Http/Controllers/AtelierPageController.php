<?php

namespace App\Http\Controllers;

use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class AtelierPageController extends Controller
{
    public function officina(): View
    {
        return view('site.atelier.officina', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function probatio(): View
    {
        return view('site.atelier.probatio', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function legal(): View
    {
        return view('site.atelier.legal', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function terms(): View
    {
        return view('site.atelier.terms', [
            'settings' => SiteSetting::current(),
        ]);
    }
}
