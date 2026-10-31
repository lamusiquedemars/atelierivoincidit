<?php

namespace App\Modules\Media\Policies;

use App\Models\User;
use Maracuja\SiteCore\Media\Models\MediaAsset;

class MediaAssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canEditContent() && $user->canAccessContent('media');
    }

    public function view(User $user, MediaAsset $media): bool
    {
        return $user->canEditContent() && $user->canAccessContent('media');
    }

    public function create(User $user): bool
    {
        return $user->canEditContent() && $user->canAccessContent('media');
    }

    public function update(User $user, MediaAsset $media): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, MediaAsset $media): bool
    {
        return $user->is_admin && $media->canBeDeleted();
    }
}
