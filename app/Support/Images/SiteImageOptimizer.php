<?php

namespace App\Support\Images;

use FilesystemIterator;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

class SiteImageOptimizer
{
    public function countPending(string $folder, bool $recursive = true, int $maxWidth = 1600): int
    {
        if (! File::isDirectory($folder)) {
            return 0;
        }

        return count($this->pendingFiles($folder, $recursive, $maxWidth));
    }

    /**
     * @return array{
     *     checked: int,
     *     resized: int,
     *     errors: int,
     *     remaining: int,
     *     stopped_early: bool,
     *     images: list<array{
     *         path: string,
     *         before_width: int,
     *         before_height: int,
     *         after_width: int,
     *         after_height: int,
     *         before_size: int,
     *         after_size: int,
     *         size_ratio: float,
     *         reduction_percent: float
     *     }>
     * }
     */
    public function optimizeDirectory(
        string $folder,
        bool $recursive = true,
        int $maxWidth = 1600,
        int $jpegQuality = 80,
        int $batchSize = 8,
        int $maxSeconds = 260,
    ): array
    {
        $result = [
            'checked' => 0,
            'resized' => 0,
            'errors' => 0,
            'remaining' => 0,
            'stopped_early' => false,
            'images' => [],
        ];

        if (! File::isDirectory($folder)) {
            return $result;
        }

        $startedAt = microtime(true);
        $pending = $this->pendingFiles($folder, $recursive, $maxWidth);

        $result['remaining'] = count($pending);

        if ($result['remaining'] === 0) {
            return $result;
        }

        $files = array_slice($pending, 0, $batchSize);

        foreach ($files as $path) {
            if ((microtime(true) - $startedAt) >= $maxSeconds) {
                $result['stopped_early'] = true;

                break;
            }

            $result['checked']++;

            try {
                $image = $this->resizeImage($path, $folder, $maxWidth, $jpegQuality);

                if ($image !== null) {
                    $result['resized']++;
                    $result['images'][] = $image;
                }
            } catch (Throwable) {
                $result['errors']++;
            }
        }

        $result['remaining'] = max(0, count($this->pendingFiles($folder, $recursive, $maxWidth)));
        $result['stopped_early'] = $result['stopped_early'] || $result['remaining'] > 0;

        return $result;
    }

    /**
     * @return list<string>
     */
    protected function pendingFiles(string $folder, bool $recursive, int $maxWidth): array
    {
        $files = $recursive
            ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS))
            : new \IteratorIterator(new \DirectoryIterator($folder));

        $pending = [];

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            if (! in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png'], true)) {
                continue;
            }

            $info = @getimagesize($file->getPathname());

            if ($info === false) {
                continue;
            }

            if (($info[0] ?? 0) > $maxWidth) {
                $pending[] = $file->getPathname();
            }
        }

        sort($pending);

        return $pending;
    }

    /**
     * @return array{
     *     path: string,
     *     before_width: int,
     *     before_height: int,
     *     after_width: int,
     *     after_height: int,
     *     before_size: int,
     *     after_size: int,
     *     size_ratio: float,
     *     reduction_percent: float
     * }|null
     */
    protected function resizeImage(string $path, string $rootFolder, int $maxWidth, int $jpegQuality): ?array
    {
        if (! is_file($path)) {
            return null;
        }

        $info = @getimagesize($path);

        if ($info === false) {
            return null;
        }

        [$width, $height, $type] = $info;

        if ($width <= $maxWidth) {
            return null;
        }

        $beforeSize = filesize($path) ?: 0;

        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG => imagecreatefrompng($path),
            default => false,
        };

        if (! $source) {
            return null;
        }

        $newHeight = (int) round($height * $maxWidth / $width);
        $target = imagecreatetruecolor($maxWidth, $newHeight);

        if ($type === IMAGETYPE_PNG) {
            imagealphablending($target, false);
            imagesavealpha($target, true);
        }

        imagecopyresampled($target, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
        imagedestroy($source);

        $ok = $type === IMAGETYPE_JPEG
            ? imagejpeg($target, $path, $jpegQuality)
            : imagepng($target, $path, 7);

        imagedestroy($target);

        if (! $ok) {
            return null;
        }

        clearstatcache(true, $path);

        $afterInfo = @getimagesize($path);
        $afterSize = filesize($path) ?: 0;
        $sizeRatio = $beforeSize > 0 ? $afterSize / $beforeSize : 1.0;

        return [
            'path' => $this->relativePath($path, $rootFolder),
            'before_width' => (int) $width,
            'before_height' => (int) $height,
            'after_width' => (int) ($afterInfo[0] ?? $maxWidth),
            'after_height' => (int) ($afterInfo[1] ?? $newHeight),
            'before_size' => $beforeSize,
            'after_size' => $afterSize,
            'size_ratio' => round($sizeRatio, 4),
            'reduction_percent' => round(max(0, 1 - $sizeRatio) * 100, 1),
        ];
    }

    protected function relativePath(string $path, string $rootFolder): string
    {
        $rootFolder = rtrim($rootFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (str_starts_with($path, $rootFolder)) {
            return str_replace(DIRECTORY_SEPARATOR, '/', substr($path, strlen($rootFolder)));
        }

        return basename($path);
    }
}
