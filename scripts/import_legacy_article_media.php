<?php

use App\Modules\Articles\Models\Article;
use App\Modules\Media\Models\MediaAsset;
use App\Support\LegacyArticleImport;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

require getcwd().'/vendor/autoload.php';

$app = require getcwd().'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$importDirectory = storage_path('app/private/imports/legacy-articles-20261116-01');
require $importDirectory.'/LegacyArticleImport.media.php';
$sourceDirectory = $importDirectory.'/source-media';
$publicDirectory = 'media/images/articles/legacy-20261116';
$sql = $importDirectory.'/ivoin2573774.sql';

$sourceFiles = glob($sourceDirectory.'/*') ?: [];
if (count($sourceFiles) !== 11) {
    throw new RuntimeException('Les 11 sources image attendues ne sont pas disponibles.');
}

$assets = [];
foreach ($sourceFiles as $source) {
    $dimensions = @getimagesize($source);
    if (! is_array($dimensions)) {
        throw new RuntimeException('Image illisible : '.basename($source));
    }

    $mime = mime_content_type($source);
    $extension = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        default => throw new RuntimeException('Format non publiable : '.basename($source)),
    };
    $checksum = hash_file('sha256', $source);
    $path = $publicDirectory.'/'.$checksum.'.'.$extension;

    if (Storage::disk('public')->exists($path) || MediaAsset::query()->where('path', $path)->exists()) {
        throw new RuntimeException('La destination média existe déjà : '.$path);
    }

    $assets[basename($source)] = compact('source', 'dimensions', 'mime', 'extension', 'checksum', 'path');
}

DB::transaction(function () use ($assets, $sql): void {
    $mediaByFilename = [];

    foreach ($assets as $filename => $asset) {
        $contents = file_get_contents($asset['source']);
        if ($contents === false || ! Storage::disk('public')->put($asset['path'], $contents)) {
            throw new RuntimeException('Écriture média impossible : '.$filename);
        }
        if (hash_file('sha256', Storage::disk('public')->path($asset['path'])) !== $asset['checksum']) {
            throw new RuntimeException('Empreinte média incohérente : '.$filename);
        }

        $media = MediaAsset::query()->create([
            'type' => 'image',
            'disk' => 'public',
            'source_disk' => 'local',
            'source_path' => 'imports/legacy-articles-20261116-01/source-media/'.$filename,
            'path' => $asset['path'],
            'original_name' => $filename,
            'display_name' => pathinfo($filename, PATHINFO_FILENAME),
            'mime_type' => $asset['mime'],
            'extension' => $asset['extension'],
            'size' => filesize($asset['source']),
            'width' => $asset['dimensions'][0],
            'height' => $asset['dimensions'][1],
            'checksum' => $asset['checksum'],
        ]);
        $mediaByFilename[$filename] = $media->id;
    }

    $records = LegacyArticleImport::records($sql, function (string $source) use ($mediaByFilename): ?int {
        $path = parse_url($source, PHP_URL_PATH) ?: $source;

        return $mediaByFilename[basename($path)] ?? null;
    });

    foreach ($records as $record) {
        $article = Article::query()->where('slug', $record['slug'])->firstOrFail();
        $cover = $record['legacy_images'][0] ?? null;
        $coverFilename = $cover ? basename(parse_url($cover, PHP_URL_PATH) ?: $cover) : null;

        $article->forceFill([
            'image_media_id' => $coverFilename ? ($mediaByFilename[$coverFilename] ?? null) : null,
            'body_blocks' => $record['body_blocks'],
        ])->save();
    }

    if (MediaAsset::query()->whereIn('id', $mediaByFilename)->count() !== 11) {
        throw new RuntimeException('Nombre de médias créés incohérent.');
    }
    if (Article::query()->whereNotNull('image_media_id')->count() !== 8) {
        throw new RuntimeException('Nombre de couvertures rattachées incohérent.');
    }
});

echo 'imported_media=11'.PHP_EOL;
