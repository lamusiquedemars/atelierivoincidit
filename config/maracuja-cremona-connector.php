<?php

return [
    'enabled' => env('MARACUJA_CREMONA_ENABLED', false),
    'preserve_local_inbox' => true,
    'endpoint' => env('MARACUJA_CREMONA_ENDPOINT'),
    'token' => env('MARACUJA_CREMONA_TOKEN'),
    'site_reference' => env('MARACUJA_CREMONA_SITE_REFERENCE'),
    'form_reference' => 'contact-v1',
    'consent_statement' => 'J’autorise une réponse à cette demande.',
    'consent_statement_version' => 'v1',
    'timeout_seconds' => 15,
    // Atelier already owns the historical table and its compatible evolution.
    'load_migrations' => false,
    'delivery_table' => 'cremona_deliveries',
];
