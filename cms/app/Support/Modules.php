<?php

namespace App\Support;

class Modules
{
    public static function enabled(string $module): bool
    {
        $offer = (string) config('maracuja.offer', 'signature');
        $allowedByOffer = config("maracuja.offers.{$offer}.{$module}");

        if ($allowedByOffer === null) {
            $allowedByOffer = true;
        }

        return (bool) $allowedByOffer && (bool) config("maracuja.modules.{$module}", false);
    }

    public static function offer(): string
    {
        return (string) config('maracuja.offer', 'signature');
    }
}
