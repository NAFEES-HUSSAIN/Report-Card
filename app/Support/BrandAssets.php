<?php

namespace App\Support;

class BrandAssets
{
    /**
     * Relative paths under /public checked in order.
     *
     * @var list<string>
     */
    public const SchoolLogoCandidates = [
        'images/branding/school-logo.png',
        'images/branding/school-logo.jpg',
        'images/branding/school-logo.jpeg',
        'images/branding/school-logo.webp',
        'images/branding/school-logo.svg',
    ];

    /**
     * Public URL for the static school logo (no DB / upload).
     */
    public static function schoolLogoUrl(): string
    {
        foreach (self::SchoolLogoCandidates as $relative) {
            $absolute = public_path($relative);

            if (is_file($absolute)) {
                return asset($relative).'?v='.filemtime($absolute);
            }
        }

        return asset('images/branding/school-logo.svg');
    }
}
