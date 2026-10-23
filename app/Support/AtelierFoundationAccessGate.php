<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Maracuja\SiteCore\Foundation\Contracts\FoundationAccessGate;

/** Maps the Core capabilities to Atelier's current administrator-only policy. */
final class AtelierFoundationAccessGate implements FoundationAccessGate
{
    public function allows(string $ability): bool
    {
        $user = auth()->user();

        if (! $user instanceof User || ! $user->is_admin) {
            return false;
        }

        return match ($ability) {
            'media.manage' => Modules::enabled('gallery'),
            'gallery.manage' => Modules::enabled('gallery'),
            default => false,
        };
    }
}
