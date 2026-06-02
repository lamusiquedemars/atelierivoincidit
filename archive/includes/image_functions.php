<?php
// includes/image_functions.php

function resize_image($path, $maxWidth = 1600, $quality = 80) {
    [$width, $height, $type] = @getimagesize($path);
    if (!$width || !$height) return false;

    // Ne rien faire si l'image est déjà petite
    if ($width <= $maxWidth) return false;

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($path);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($path);
            // Conversion PNG → JPEG pour réduire le poids
            break;
        default:
            return false; // ignore les autres formats
    }

    $newHeight = (int) ($height * $maxWidth / $width);
    $newImage = imagecreatetruecolor($maxWidth, $newHeight);

    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
    imagedestroy($image);

    // Sauvegarde JPEG seulement si redimensionné
    $ok = imagejpeg($newImage, $path, $quality);
    imagedestroy($newImage);

    return $ok;
}
