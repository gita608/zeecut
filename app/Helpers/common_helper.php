<?php
use App\Models\Setting;

if (!function_exists('format_price')) {
    function format_price($amount)
    {
        return '₹ ' . number_format($amount);
    }
}
