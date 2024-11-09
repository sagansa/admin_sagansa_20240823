<?php

namespace App\Helpers;

class ViteHelper
{
    public static function asset($path)
    {
        if (app()->environment('local')) {
            return app('vite')->asset($path);
        }

        $manifest = self::getManifest();
        
        // Jika path ada di manifest
        if (isset($manifest[$path])) {
            return asset('build/' . $manifest[$path]['file']);
        }

        // Fallback ke path asli
        return asset($path);
    }

    private static function getManifest()
    {
        static $manifest = null;
        
        if (!$manifest) {
            $manifestPath = public_path('build/manifest.json');

            if (!file_exists($manifestPath)) {
                throw new \Exception('The Vite manifest does not exist. Please run `npm run build`');
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        return $manifest;
    }
}
