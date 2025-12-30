<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value): bool
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return false;
        }

        // Convert value based on type
        if ($setting->type === 'boolean') {
            $value = $value ? 'true' : 'false';
        } elseif ($setting->type === 'json') {
            $value = json_encode($value);
        } else {
            $value = (string) $value;
        }

        $setting->update(['value' => $value]);

        // Clear cache
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');
        Cache::forget('public_settings');

        return true;
    }

    /**
     * Get all settings
     */
    public static function getAll(): array
    {
        return Cache::rememberForever('all_settings', function () {
            $settings = self::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Get all public settings
     */
    public static function getPublic(): array
    {
        return Cache::rememberForever('public_settings', function () {
            $settings = self::where('is_public', true)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        $settings = self::where('group', $group)->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = [
                'value' => self::castValue($setting->value, $setting->type),
                'label' => $setting->label,
                'description' => $setting->description,
                'type' => $setting->type,
            ];
        }

        return $result;
    }

    /**
     * Update multiple settings at once
     */
    public static function setMany(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }

        return true;
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = self::all();

        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }

        Cache::forget('all_settings');
        Cache::forget('public_settings');
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => $value === 'true' || $value === '1',
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
