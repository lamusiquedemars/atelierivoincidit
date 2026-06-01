<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Gallery\Models\GalleryImage;
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
            'social_links' => [
                'Instagram : @ivo_incidit' => 'https://instagram.com/ivo_incidit',
            ],
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

        $galleryImages = [
            [
                'image' => 'showcase-hausses.jpeg',
                'title' => 'Hausses d’archets',
                'caption' => 'Hausses modernes et baroques.',
            ],
            [
                'image' => 'showcase-c-1.jpeg',
                'title' => 'Archets de violoncelle',
                'caption' => 'Tête et hausse d’archet, en ipé et en cumaru.',
            ],
            [
                'image' => 'showcase-v-1.jpeg',
                'title' => 'Tête d’archet de violon',
                'caption' => 'Archet de violon en amarante, plaque de tête en bois satiné.',
            ],
            [
                'image' => 'showcase-v-2.jpeg',
                'title' => 'Tête d’archet de violon',
                'caption' => 'Archet de violon en satiné, plaque de tête en ébène.',
            ],
            [
                'image' => 'showcase-cb-1.jpeg',
                'title' => 'Archet baroque de violoncelle',
                'caption' => 'Archet baroque de violoncelle en cumaru, hausse et bouton en amourette.',
            ],
            [
                'image' => 'showcase-ve-1.jpeg',
                'title' => 'Archet de violon pour enfant',
                'caption' => 'Archet de violon pour enfant en cumaru blond, garniture en fil de lin coloré.',
            ],
            [
                'image' => 'showcase-vb-1.jpeg',
                'title' => 'Archet baroque de violon',
                'caption' => 'Archet baroque de violon en cumaru, hausse et bouton en amourette.',
            ],
            [
                'image' => 'showcase-vb-2.jpeg',
                'title' => 'Tête d’archet baroque de violon',
                'caption' => 'Archet baroque de violon en massaranbuda.',
            ],
            [
                'image' => 'showcase-v-3.jpeg',
                'title' => 'Archet de violon',
                'caption' => 'Archet de violon en cumaru, poucette en cuir et garniture en acier inoxydable.',
            ],
            [
                'image' => 'showcase-v-4.jpeg',
                'title' => 'Têtes d’archet de violon',
                'caption' => 'Archets de violon en cumaru et wamara, plaques de tête en ébène et en os.',
            ],
        ];

        foreach ($galleryImages as $position => $item) {
            $path = '/assets/images/' . $item['image'];
            $dimensions = @getimagesize(public_path(ltrim($path, '/')));

            GalleryImage::query()->updateOrCreate(['image_path' => $path], [
                'title' => $item['title'],
                'caption' => $item['caption'],
                'alt_text' => $item['title'],
                'width' => $dimensions[0] ?? 1600,
                'height' => $dimensions[1] ?? 1000,
                'position' => $position + 1,
                'is_published' => true,
            ]);
        }
    }
}
