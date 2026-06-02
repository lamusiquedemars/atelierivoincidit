<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (config('maracuja.theme') === 'atelier') {
            $this->call(AtelierInitialSeeder::class);

            return;
        }

        User::query()->updateOrCreate([
            'email' => 'admin@maracuja.test',
        ], [
            'name' => 'Maracuja Admin',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'site_name' => 'Maracuja CMS',
            'baseline' => 'Sites vitrines administrables, sobres et sur mesure.',
            'default_seo_title' => 'Maracuja CMS',
            'default_seo_description' => 'Atelier Ivo Incidit présente des archets contemporains, des archets anciens et le savoir-faire d’un atelier dédié aux musiciens.',
            'default_og_image_path' => '/demo/theme-system.svg',
            'contact_email' => 'contact@maracuja.test',
            'social_links' => [
                'Instagram' => 'https://instagram.com',
                'LinkedIn' => 'https://linkedin.com',
            ],
        ]);

        collect([
            [
                'key' => 'home.hero.cta_label',
                'label' => 'CTA principal home',
                'group' => 'Accueil',
                'type' => 'text',
                'value' => 'Présenter un projet',
                'help_text' => 'Texte court du bouton principal de la home.',
            ],
            [
                'key' => 'home.hero.secondary_cta_label',
                'label' => 'CTA secondaire home',
                'group' => 'Accueil',
                'type' => 'text',
                'value' => 'Voir les services',
                'help_text' => 'Texte court du bouton secondaire de la home.',
            ],
            [
                'key' => 'home.intro.title',
                'label' => 'Titre introduction home',
                'group' => 'Accueil',
                'type' => 'text',
                'value' => 'Le socle des offres Essence et Signature',
                'help_text' => 'Titre court de la section introduction.',
            ],
            [
                'key' => 'home.intro.text',
                'label' => 'Texte introduction home',
                'group' => 'Accueil',
                'type' => 'textarea',
                'value' => 'Un site vitrine administré, sans surcharge, avec les modules utiles au client et une base front propre.',
                'help_text' => 'Texte court. Eviter les paragraphes longs.',
            ],
            [
                'key' => 'services.essence.price',
                'label' => 'Prix Essence',
                'group' => 'Services',
                'type' => 'price',
                'value' => 'À partir de 1500',
                'help_text' => 'Prix ou mention courte affichée sur la carte Essence.',
            ],
            [
                'key' => 'services.signature.price',
                'label' => 'Prix Signature',
                'group' => 'Services',
                'type' => 'price',
                'value' => 'Sur devis cadre',
                'help_text' => 'Prix ou mention courte affichée sur la carte Signature.',
            ],
            [
                'key' => 'services.univers.price',
                'label' => 'Prix Univers',
                'group' => 'Services',
                'type' => 'price',
                'value' => 'Sur devis métier',
                'help_text' => 'Prix ou mention courte affichée sur la carte Univers.',
            ],
        ])->each(fn (array $slot) => ContentSlot::query()->updateOrCreate(
            ['key' => $slot['key']],
            $slot + ['is_locked' => true],
        ));

        Page::query()->updateOrCreate(['slug' => 'accueil'], [
            'title' => 'Accueil',
            'template' => 'landing',
            'excerpt' => 'Une démo du starter Maracuja CMS.',
            'hero_title' => 'Un site clair. Une administration simple.',
            'hero_subtitle' => 'Maracuja CMS fournit un socle Laravel + Filament pour créer des sites vitrines administrables, sans tableau de bord inutile.',
            'body_blocks' => [
                'intro_title' => 'Le socle des offres Essence et Signature',
                'intro_text' => 'Un site vitrine administré, sans surcharge, avec les modules utiles au client et une base front propre.',
                'cta_label' => 'Présenter un projet',
                'secondary_cta_label' => 'Voir les services',
            ],
            'seo_title' => 'Maracuja CMS - Starter vitrine administrable',
            'seo_description' => 'Starter Laravel + Filament pour sites vitrines administrables.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Page::query()->updateOrCreate(['slug' => 'methode'], [
            'title' => 'Méthode',
            'template' => 'default',
            'excerpt' => 'La méthode Maracuja CMS en version starter.',
            'hero_title' => 'Une structure avant les options',
            'hero_subtitle' => 'Chaque site part d’un socle commun puis reçoit seulement les modules utiles au client.',
            'body_blocks' => [
                'section_1' => 'Cadrer les pages, les contenus et les modules avant de développer.',
                'section_2' => 'Garder un admin court, lisible et orienté métier.',
            ],
            'is_published' => true,
            'published_at' => now(),
        ]);

        Page::query()->updateOrCreate(['slug' => 'services'], [
            'title' => 'Services',
            'template' => 'services',
            'excerpt' => 'Les offres type portées par le starter.',
            'hero_title' => 'Des sites vitrines administrables, sans usine à gaz',
            'hero_subtitle' => 'Essence pour aller vite et bien. Signature pour une présence plus complète. Univers couvre les besoins métier cadrés.',
            'body_blocks' => [
                'essence_price' => 'À partir de 1500',
                'signature_price' => 'Sur devis cadre',
                'univers_price' => 'Sur devis métier',
            ],
            'seo_title' => 'Services - Maracuja CMS',
            'seo_description' => 'Offres Essence, Signature et Univers pour sites vitrines administrables.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        NewsPost::query()->updateOrCreate(['slug' => 'maracuja-cms-v1'], [
            'title' => 'Maracuja CMS V1',
            'excerpt' => 'Le starter prend forme avec Pages, Actualités, Galerie et Contact.',
            'content' => '<p>Cette première version sert de base aux sites vitrines administrables.</p>',
            'is_published' => true,
            'is_pinned' => true,
            'has_detail_page' => true,
            'published_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        NewsPost::query()->updateOrCreate(['slug' => 'front-system'], [
            'title' => 'Un front system maison',
            'excerpt' => 'Le starter embarque ses composants CSS, Blade et JS sans framework visible.',
            'content' => '<p>Le socle front permet de composer des pages propres sans repartir d’un CSS spécifique à chaque client.</p>',
            'is_published' => true,
            'is_pinned' => false,
            'has_detail_page' => true,
            'published_at' => now()->subDay(),
            'expires_at' => now()->addDays(29),
        ]);

        NewsPost::query()->updateOrCreate(['slug' => 'admin-simplifiee'], [
            'title' => 'Une admin limitée aux modules utiles',
            'excerpt' => 'Filament affiche seulement les sections activées pour le projet client.',
            'content' => '<p>Le client garde un tableau de bord lisible, orienté contenu et sans surcharge inutile.</p>',
            'is_published' => true,
            'is_pinned' => false,
            'has_detail_page' => false,
            'published_at' => now()->subDays(2),
            'expires_at' => now()->addDays(28),
        ]);

        SiteNotice::query()->updateOrCreate(['title' => 'Annonce demo'], [
            'message' => 'Ce bloc est une annonce courte indépendante des pages. Si aucune annonce active n’existe, rien ne s’affiche.',
            'link_label' => 'Contacter',
            'link_url' => '/contact',
            'placement' => 'home',
            'tone' => 'info',
            'is_published' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addDays(15),
        ]);

        $homeGallery = Gallery::query()->updateOrCreate(['slug' => 'home'], [
            'title' => 'Galerie principale',
            'intro' => 'Le Media System gère alt, légende, credit, dimensions et lightbox.',
            'position' => 1,
            'is_published' => true,
        ]);

        GalleryImage::query()->updateOrCreate(['title' => 'Admin simple'], [
            'gallery_id' => $homeGallery->id,
            'caption' => 'Une administration limitée aux modules activés.',
            'alt_text' => 'Interface d’administration simple limitée aux modules utiles.',
            'credit' => 'Maracuja CMS',
            'image_path' => '/demo/admin-simple.svg',
            'width' => 1200,
            'height' => 800,
            'position' => 1,
            'is_published' => true,
        ]);

        GalleryImage::query()->updateOrCreate(['title' => 'Composants front'], [
            'gallery_id' => $homeGallery->id,
            'caption' => 'Des sections, cartes, CTA, galeries et variantes réutilisables.',
            'alt_text' => 'Exemple abstrait de composants front organisés.',
            'credit' => 'Maracuja CMS',
            'image_path' => '/demo/front-system.svg',
            'width' => 1200,
            'height' => 800,
            'position' => 2,
            'is_published' => true,
        ]);

        GalleryImage::query()->updateOrCreate(['title' => 'Thèmes clients'], [
            'gallery_id' => $homeGallery->id,
            'caption' => 'Une structure commune peut prendre plusieurs ambiances.',
            'alt_text' => 'Variantes de thèmes pour sites clients.',
            'credit' => 'Maracuja CMS',
            'image_path' => '/demo/theme-system.svg',
            'width' => 1200,
            'height' => 800,
            'position' => 3,
            'is_published' => true,
        ]);
    }
}
