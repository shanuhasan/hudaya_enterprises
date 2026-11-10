<?php

namespace App\Support;

/**
 * Resolves an image slot path (relative to public/), e.g. "images/placeholders/hero/about.jpg".
 *
 * - exists:  false when the file is missing → <x-media> renders only the placeholder (no 404).
 * - srcset:  when a smaller copy "name@800.jpg" exists and is not older than the original,
 *            phones get the small file. A replaced photo makes the old copy stale, so it is ignored.
 *
 * Same behaviour as the Phase 1 build step (static-site/build/html-include.js).
 */
class ImageSlot
{
    /** @var array<string, array{exists: bool, srcset: ?string, sizes: ?string}> */
    private static array $cache = [];

    public static function resolve(?string $src): array
    {
        if (! $src) {
            return ['exists' => false, 'srcset' => null, 'sizes' => null];
        }

        return self::$cache[$src] ??= self::inspect($src);
    }

    private static function inspect(string $src): array
    {
        $file = public_path($src);
        $result = ['exists' => is_file($file), 'srcset' => null, 'sizes' => null];

        if (! $result['exists'] || ! str_ends_with($src, '.jpg')) {
            return $result;
        }

        $small = substr($src, 0, -4).'@800.jpg';
        $smallFile = public_path($small);

        if (! is_file($smallFile) || filemtime($smallFile) < filemtime($file)) {
            return $result;
        }

        $width = @getimagesize($file)[0] ?? 0;

        if ($width > 800) {
            $result['srcset'] = asset($small).' 800w, '.asset($src).' '.$width.'w';
            $result['sizes'] = str_contains($src, '/hero/') ? '100vw' : '(min-width: 64rem) 50vw, 100vw';
        }

        return $result;
    }
}
