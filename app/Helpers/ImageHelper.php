<?php

namespace App\Helpers;

class ImageHelper
{
    public static function getImageUrl($path)
    {
        $apiUrl = config('services.api.url', 'https://api.sagansa.id');
        return $apiUrl . '/storage/' . $path;
    }
}
