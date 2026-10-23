<?php

return [
    // Les tables historiques sont conservées pendant la transition. Elles ont
    // le même schéma que le Core : aucune migration de données ne s'exécute.
    'load_migrations' => false,
    'public_disk' => config('maracuja.media.disk', 'public'),
    'originals_disk' => config('maracuja.media.originals_disk', 'local'),
    'processing_disk' => 'local',
    'images_directory' => config('maracuja.media.images_directory', 'media/images'),
    'documents_directory' => config('maracuja.media.documents_directory', 'media/documents'),
    'videos_directory' => config('maracuja.media.videos_directory', 'media/videos'),
    'originals_directory' => config('maracuja.media.originals_directory', 'media/originals'),
    'image_max_size_kb' => config('maracuja.media.image_max_size_kb', 25 * 1024),
    'document_max_size_kb' => config('maracuja.media.document_max_size_kb', 15 * 1024),
    'video_max_size_kb' => config('maracuja.media.video_max_size_kb', 100 * 1024),
    'image_max_pixels' => config('maracuja.media.image_max_pixels', 36_000_000),
    'image_variant_widths' => config('maracuja.media.image_variant_widths', [640, 960, 1440, 1920]),
    'image_webp_quality' => config('maracuja.media.image_webp_quality', 82),
    'ffmpeg_binary' => config('maracuja.media.ffmpeg_binary', 'ffmpeg'),
    'video_thumbnail_second' => config('maracuja.media.video_thumbnail_second', 1),
    'video_thumbnails_directory' => config('maracuja.media.video_thumbnails_directory', 'media/video-thumbnails'),
    'mime_types' => config('maracuja.media.mime_types', []),
];
