<?php

return [
    // Même principe que Media : les migrations locales restent l'historique
    // de référence tant que la bascule est réversible.
    'load_migrations' => false,
    'system_slug' => config('maracuja.gallery.slug', 'home'),
    'system_slugs' => [config('maracuja.gallery.slug', 'home'), 'atelier-home'],
    'legacy_disk' => 'public',
];
