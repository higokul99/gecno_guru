<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageConversionService
{
    /**
     * Convert an uploaded image to WebP and store it.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory Relative directory under media root
     * @param string $filename Base filename (without extension)
     * @return string|false Relative path to the saved WebP image
     */
    public function convertAndStore($file, $directory, $filename)
    {
        $mediaDisk = Storage::disk('media');
        
        // Ensure directory exists
        if (!$mediaDisk->exists($directory)) {
            $mediaDisk->makeDirectory($directory);
        }

        $image = null;
        $extension = strtolower($file->getClientOriginalExtension());

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = @imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $image = @imagecreatefrompng($file->getRealPath());
                break;
            case 'webp':
                $image = @imagecreatefromwebp($file->getRealPath());
                break;
            case 'gif':
                $image = @imagecreatefromgif($file->getRealPath());
                break;
        }

        if (!$image) {
            return false;
        }

        // Preserve transparency for PNG/WebP
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $webpFilename = "{$filename}.webp";
        $relativeWebpPath = "{$directory}/{$webpFilename}";
        $fullWebpPath = config('filesystems.disks.media.root') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeWebpPath);

        // Save as WebP
        if (imagewebp($image, $fullWebpPath, 80)) {
            imagedestroy($image);
            return $relativeWebpPath;
        }

        imagedestroy($image);
        return false;
    }

    /**
     * Get a timestamp for India Standard Time.
     *
     * @return string
     */
    public function istTimestamp()
    {
        return now()->timezone('Asia/Kolkata')->format('YmdHis');
    }
}
