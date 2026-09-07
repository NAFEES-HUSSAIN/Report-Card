<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    public const LogoPath = 'logo_path';

    public static function getValue(string $key, ?string $default = null): ?string
    {
        $value = Cache::rememberForever("site_setting.{$key}", function () use ($key) {
            return static::query()->where('key', $key)->value('value');
        });

        return $value ?? $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );

        Cache::forget("site_setting.{$key}");
    }

    public static function logoPath(): ?string
    {
        $path = static::getValue(self::LogoPath);

        return filled($path) ? $path : null;
    }

    public static function logoUrl(): ?string
    {
        $path = static::logoPath();

        if ($path === null || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public static function storeLogo(UploadedFile $file): string
    {
        static::deleteStoredLogoFile();

        $path = $file->store('branding', 'public');
        static::setValue(self::LogoPath, $path);

        return $path;
    }

    public static function removeLogo(): void
    {
        static::deleteStoredLogoFile();
        static::setValue(self::LogoPath, null);
    }

    private static function deleteStoredLogoFile(): void
    {
        $path = static::query()->where('key', self::LogoPath)->value('value');

        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
