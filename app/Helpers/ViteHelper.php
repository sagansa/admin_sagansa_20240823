<?php

namespace App\Helpers;

class ViteHelper
{
    public static function asset($path)
    {
        try {
            $manifest = self::getManifest();
            return asset('build/' . $manifest[$path]['file']);
        } catch (\Exception $e) {
            // Fallback jika manifest tidak ada
            return asset('build/' . $path);
        }
    }

    private static function getManifest()
    {
        $manifestPath = public_path('build/manifest.json');

        // Coba load backup jika manifest utama tidak ada
        if (!file_exists($manifestPath) && file_exists($manifestPath . '.backup')) {
            copy($manifestPath . '.backup', $manifestPath);
        }

        if (!file_exists($manifestPath)) {
            throw new \Exception('Vite manifest not found');
        }

        return json_decode(file_get_contents($manifestPath), true);
    }
}
