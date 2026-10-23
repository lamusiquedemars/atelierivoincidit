<?php

declare(strict_types=1);

namespace App\Support;

use Maracuja\SiteCore\Foundation\Contracts\FoundationAccessGate;
use Maracuja\SiteCore\Media\Contracts\MediaAccessGate;

final class AtelierMediaAccessGate implements MediaAccessGate
{
    public function __construct(private readonly FoundationAccessGate $access) {}

    public function allowsMediaManagement(): bool
    {
        return $this->access->allows('media.manage');
    }
}
