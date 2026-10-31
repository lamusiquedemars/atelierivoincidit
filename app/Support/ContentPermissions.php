<?php

namespace App\Support;

use App\Models\User;
use App\Modules\Arcus\Models\Bow;
use App\Modules\Arcus\Models\Wood;
use App\Modules\Articles\Models\Article;
use App\Modules\Contact\Models\ContactSubmission;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Maracuja\SiteCore\Gallery\Models\Gallery;
use Maracuja\SiteCore\Media\Models\MediaAsset;

final class ContentPermissions
{
    public static function labels(): array
    {
        return [
            'pages' => 'Pages', 'notices' => 'Annonces courtes',
            'content_slots' => 'Contenus courts', 'news' => 'Actualités',
            'articles' => 'Articles', 'media' => 'Médias', 'gallery' => 'Galeries',
            'inquiries' => 'Demandes reçues', 'arcus' => 'Catalogue d’archets et outils',
        ];
    }

    public static function labelsForForm(): array
    {
        $labels = self::labels();
        $modules = ['inquiries' => 'contact_form', 'media' => 'gallery'];

        foreach ($labels as $key => $label) {
            // Older sites provide media permanently without a module flag.
            if ($key === 'media' && ! isset($modules[$key]) && config('maracuja.modules.media') === null) {
                continue;
            }

            if (! Modules::enabled($modules[$key] ?? $key)) {
                $labels[$key] = $label.' (module inactif)';
            }
        }

        return $labels;
    }

    public static function allows(User $user, ?string $model): bool
    {
        $permission = match ($model) {
            Page::class => 'pages', SiteNotice::class => 'notices', ContentSlot::class => 'content_slots',
            NewsPost::class => 'news', Article::class => 'articles', MediaAsset::class => 'media',
            Gallery::class => 'gallery', ContactSubmission::class => 'inquiries', Bow::class, Wood::class => 'arcus',
            default => null,
        };

        if ($permission !== null) {
            return $user->canAccessContent($permission);
        }

        return $user->isAdministrator() || $user->content_permissions === null
            || ! $model || ! is_a($model, Model::class, true);
    }
}
