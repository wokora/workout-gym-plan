<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class Vite
{
    /**
     * Generate HTML tags for Vite assets.
     *
     * @param array|string $entrypoints
     * @return HtmlString
     */
    public static function assets($entrypoints = [])
    {
        if (is_string($entrypoints)) {
            $entrypoints = [$entrypoints];
        }

        // Hot Reloading (Vite dev server)
        $hotPath = public_path('hot');
        if (file_exists($hotPath)) {
            $url = rtrim(trim(file_get_contents($hotPath)));
            $html = ["<script type=\"module\" src=\"{$url}/@vite/client\"></script>"];
            foreach ($entrypoints as $entry) {
                if (str_ends_with($entry, '.css')) {
                    $html[] = "<link rel=\"stylesheet\" href=\"{$url}/{$entry}\" />";
                } else {
                    $html[] = "<script type=\"module\" src=\"{$url}/{$entry}\"></script>";
                }
            }
            return new HtmlString(implode("\n", $html));
        }

        // Production Build
        $manifestPath = public_path('build/.vite/manifest.json');
        if (!file_exists($manifestPath)) {
            $manifestPath = public_path('build/manifest.json');
        }

        if (!file_exists($manifestPath)) {
            return new HtmlString('');
        }

        $manifest = json_decode(file_get_contents($manifestPath), true) ?? [];
        $html = [];

        foreach ($entrypoints as $entry) {
            if (!isset($manifest[$entry])) {
                continue;
            }

            $item = $manifest[$entry];
            if (isset($item['css']) && is_array($item['css'])) {
                foreach ($item['css'] as $cssFile) {
                    $html[] = '<link rel="stylesheet" href="' . asset('build/' . $cssFile) . '" />';
                }
            }

            if (str_ends_with($entry, '.css')) {
                $html[] = '<link rel="stylesheet" href="' . asset('build/' . $item['file']) . '" />';
            } else {
                $html[] = '<script type="module" src="' . asset('build/' . $item['file']) . '"></script>';
            }
        }

        return new HtmlString(implode("\n", array_unique($html)));
    }
}
