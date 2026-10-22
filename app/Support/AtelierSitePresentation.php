<?php

declare(strict_types=1);

namespace App\Support;

use Maracuja\SiteCore\Foundation\Contracts\SitePresentation;

final class AtelierSitePresentation implements SitePresentation
{
    public function theme(): string
    {
        return 'atelier';
    }

    public function galleryPreset(): ?string
    {
        return 'carousel';
    }

    public function galleryPresetLabel(): ?string
    {
        return 'Carrousel atelier';
    }
}
