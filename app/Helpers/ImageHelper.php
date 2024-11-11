<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getImageUrl($path, $thumbnail = false)
    {
        $apiUrl = config('services.api.url', 'https://api.sagansa.id');
        $baseUrl = $apiUrl . '/storage/';

        if ($thumbnail) {
            // Tambahkan prefix thumbnail jika diperlukan
            return $baseUrl . 'thumbnails/' . $path;
        }

        return $baseUrl . $path;
    }
}
