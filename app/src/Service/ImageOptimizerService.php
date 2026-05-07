<?php

namespace App\Service;

class ImageOptimizerService
{
    public function convertToWebp(string $sourcePath, int $quality = 80): string
    {
        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sourcePath);

        if (file_exists($webpPath)) {
            return $webpPath;
        }

        $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

        $image = match($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($sourcePath),
            'png'         => imagecreatefrompng($sourcePath),
            default       => throw new \InvalidArgumentException("Format non supporté : $ext"),
        };

        imagewebp($image, $webpPath, $quality);
        unset($image); // 

        return $webpPath;
    }

    public function convertDirectory(string $dirPath): void
    {
        $files = glob($dirPath . '/*.{jpg,jpeg,png}', GLOB_BRACE);

        foreach ($files as $file) {
            $this->convertToWebp($file);
        }
    }
}
