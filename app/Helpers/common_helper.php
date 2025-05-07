<?php
use App\Models\Setting;

if (!function_exists('format_price')) {
    function format_price($amount)
    {
        return '₹ ' . number_format($amount);
    }

    if (!function_exists('get_setting')) {
        function get_setting($key, $default = null)
        {
            // $setting = cache()->remember("setting_{$key}", 60*60, function () use ($key) {
                return Setting::where('key', $key)->value('value');
            // });
    
            // return $setting ?? $default;
        }
    }

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
