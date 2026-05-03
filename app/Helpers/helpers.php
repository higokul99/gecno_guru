<?php

if (!function_exists('media_url')) {
    /**
     * Get the public URL for a media file.
     *
     * @param string|null $path
     * @return string
     */
    function media_url($path)
    {
        if (empty($path)) {
            return '';
        }

        // If it's already a full URL, return it
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $baseUrl = config('filesystems.disks.media.url');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
