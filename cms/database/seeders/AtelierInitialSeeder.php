<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtelierInitialSeeder extends Seeder
{
    public function run(): void
    {
        $legacyUser = DB::connection('legacy')
            ->table('users')
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if ($legacyUser !== null) {
            User::query()->updateOrCreate([
                'email' => $legacyUser->email,
            ], [
                'name' => $legacyUser->username ?: 'Admin Atelier',
                'password' => $legacyUser->password_hash,
                'is_admin' => true,
            ]);
        }

        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'site_name' => 'Atelier Ivo Incidit',
            'baseline' => 'Archets contemporains, savoir-faire ancien.',
            'default_seo_title' => 'Atelier Ivo Incidit - Archetier',
            'default_seo_description' => 'Atelier d’archetier dédié aux archets contemporains, aux bois anciens et aux instruments à cordes.',
            'logo_path' => '/assets/images/blason-ivo-incidit2.png',
            'contact_email' => 'contact@atelierivoincidit.fr',
            'social_links' => [],
        ]);

        Page::query()->updateOrCreate(['slug' => 'accueil'], [
            'title' => 'Accueil',
            'template' => 'landing',
            'excerpt' => 'Accueil de l’Atelier Ivo Incidit.',
            'hero_title' => 'Atelier Ivo Incidit',
            'hero_subtitle' => 'Archets contemporains, gestes anciens et matières choisies.',
            'body_blocks' => [
                'intro_title' => 'Un atelier dédié à l’archet',
                'intro_text' => 'La migration Maracuja CMS est en cours. Le site actuel reste la référence pendant la reconstruction.',
            ],
            'seo_title' => 'Atelier Ivo Incidit - Archetier',
            'seo_description' => 'Archets contemporains, atelier et savoir-faire.',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
