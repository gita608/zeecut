<?php

use App\Models\Setting;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $setting = cache()->remember("setting_{$key}", 60*60, function () use ($key) {
            return Setting::where('key', $key)->value('value');
        });

        return $setting ?? $default;
    }
}

if (!function_exists('set_setting')) {
    function set_setting($key, $value)
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        cache()->forget("setting_{$key}");

        return $setting;
    }
}
