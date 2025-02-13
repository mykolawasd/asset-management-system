<?php

function e($var) {
    echo htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

function h($var): string {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

function dd($var) {
    var_dump($var);
    die();
}

function truncateHtml($text, $maxLength) {
    if (strlen($text) <= $maxLength) {
        return $text;
    }

    $truncated = substr($text, 0, $maxLength);
    $lastSpace = strrpos($truncated, ' ');

    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }

    return $truncated . '...';
}

function resizeImage($sourcePath, $destPath, $maxWidth, $maxHeight) {
    list($width, $height, $imageType) = getimagesize($sourcePath);
    $ratio = $width / $height;
    if ($width > $maxWidth || $height > $maxHeight) {
        if ($maxWidth / $maxHeight > $ratio) {
            $newHeight = $maxHeight;
            $newWidth = $maxHeight * $ratio;
        } else {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $ratio;
        }
        $newWidth  = (int) round($newWidth);
        $newHeight = (int) round($newHeight);
    } else {
        // Image is already resized
        copy($sourcePath, $destPath);
        return;
    }
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $srcImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $srcImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $srcImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return;
    }
    
    $dstImage = imagecreatetruecolor($newWidth, $newHeight);
    
    if ($imageType == IMAGETYPE_PNG) {
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
    }
    
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($dstImage, $destPath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($dstImage, $destPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($dstImage, $destPath);
            break;
    }
    
    imagedestroy($srcImage);
    imagedestroy($dstImage);
}

function deleteFileIfExists(string $fileUrl): bool {
    $filePath = ltrim($fileUrl, '/');
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}
