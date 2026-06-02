<?php
/**
 * Fonctions images réutilisables.
 */

function resize_image(string $path, int $maxWidth = 1600, int $jpegQuality = 80): bool
{
    if (!is_file($path)) {
        return false;
    }

    $info = @getimagesize($path);

    if ($info === false) {
        return false;
    }

    [$width, $height, $type] = $info;

    if ($width <= $maxWidth) {
        return false;
    }

    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($path);
            break;

        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($path);
            break;

        default:
            return false;
    }

    if (!$source) {
        return false;
    }

    $newHeight = (int) round($height * $maxWidth / $width);
    $target = imagecreatetruecolor($maxWidth, $newHeight);

    // Préserve la transparence des PNG.
    if ($type === IMAGETYPE_PNG) {
        imagealphablending($target, false);
        imagesavealpha($target, true);
    }

    imagecopyresampled(
        $target,
        $source,
        0,
        0,
        0,
        0,
        $maxWidth,
        $newHeight,
        $width,
        $height
    );

    imagedestroy($source);

    if ($type === IMAGETYPE_JPEG) {
        $ok = imagejpeg($target, $path, $jpegQuality);
    } else {
        $ok = imagepng($target, $path, 7);
    }

    imagedestroy($target);

    return $ok;
}

function optimize_images_in_directory(string $folder, bool $recursive = true): array
{
    $result = [
        'checked' => 0,
        'resized' => 0,
        'errors' => 0,
    ];

    if (!is_dir($folder)) {
        return $result;
    }

    $extensions = ['jpg', 'jpeg', 'png'];

    if ($recursive) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS)
        );
    } else {
        $files = new IteratorIterator(
            new DirectoryIterator($folder)
        );
    }

    foreach ($files as $file) {
        if (!$file->isFile()) {
            continue;
        }

        $extension = strtolower($file->getExtension());

        if (!in_array($extension, $extensions, true)) {
            continue;
        }

        $result['checked']++;

        try {
            if (resize_image($file->getPathname())) {
                $result['resized']++;
            }
        } catch (Throwable $e) {
            $result['errors']++;
        }
    }

    return $result;
}