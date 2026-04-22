<?php

class ImageScaler
{
    private static int $targetSize = 800;

    public static function scaleImageIfTooBig(string $filePath)
    {
        if (str_contains($filePath, ".png") || str_contains($filePath, ".PNG")) {
            ImageScaler::handlePng($filePath);
        }

        if (str_contains($filePath, ".jpg") || str_contains($filePath, ".JPG")) {
            ImageScaler::handleJPEG($filePath);
        }

        if (str_contains($filePath, ".jpeg") || str_contains($filePath, ".JPEG")) {
            ImageScaler::handleJPEG($filePath);
        }

        if (str_contains($filePath, ".gif") || str_contains($filePath, ".GIF")) {
            ImageScaler::handleGIF($filePath);
        }
    }

    public static function handleGIF($filePath)
    {
        $image = imagecreatefromgif($filePath);
        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = ImageScaler::$targetSize;
        $newHeight = ImageScaler::$targetSize;

        if ($width > ImageScaler::$targetSize || $height > ImageScaler::$targetSize) {
            if ($width > $height) {
                $ratio = ImageScaler::$targetSize / $width;
                $newWidth = ImageScaler::$targetSize;
                $newHeight = floor($height * $ratio);
            } else if ($width < $height) {
                $ratio = ImageScaler::$targetSize / $height;
                $newWidth = floor($width * $ratio);
                $newHeight = ImageScaler::$targetSize;
            }

            $scaledImg = imagescale($image, $newWidth, $newHeight);
            imagegif($scaledImg, $filePath);
        }
    }

    public static function handleJPEG($filePath)
    {
        $image = imagecreatefromjpeg($filePath);
        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = ImageScaler::$targetSize;
        $newHeight = ImageScaler::$targetSize;

        if ($width > ImageScaler::$targetSize || $height > ImageScaler::$targetSize) {
            if ($width > $height) {
                $ratio = ImageScaler::$targetSize / $width;
                $newWidth = ImageScaler::$targetSize;
                $newHeight = floor($height * $ratio);
            } else if ($width < $height) {
                $ratio = ImageScaler::$targetSize / $height;
                $newWidth = floor($width * $ratio);
                $newHeight = ImageScaler::$targetSize;
            }

            $scaledImg = imagescale($image, $newWidth, $newHeight);
            imagejpeg($scaledImg, $filePath);
        }
    }

    public static function handlePng($filePath): void
    {

        $image = imagecreatefrompng($filePath);
        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = ImageScaler::$targetSize;
        $newHeight = ImageScaler::$targetSize;

        if ($width > ImageScaler::$targetSize || $height > ImageScaler::$targetSize) {
            if ($width > $height) {
                $ratio = ImageScaler::$targetSize / $width;
                $newWidth = ImageScaler::$targetSize;
                $newHeight = floor($height * $ratio);
            } else if ($width < $height) {
                $ratio = ImageScaler::$targetSize / $height;
                $newWidth = floor($width * $ratio);
                $newHeight = ImageScaler::$targetSize;
            }

            $scaledImg = imagescale($image, $newWidth, $newHeight);
            imagepng($scaledImg, $filePath);
        }
    }
}
