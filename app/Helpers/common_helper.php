<?php
use App\Models\PayLater;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

if (!function_exists('getLastQuery')) {
    function getLastQuery()
    {
        $queries = DB::getQueryLog();
        $query = end($queries);

        if (!$query) return null;

        return vsprintf(
            str_replace('?', "'%s'", $query['query']),
            $query['bindings']
        );
    }
}

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

    if (!function_exists('is_payLater')) {
        function is_payLater($user_id)
        {
            if ($user_id > 0) {
                $data = PayLater::where('user_id', $user_id)->first();
                if (!empty($data)) {
                    if ($data->status == 1 && $data->credit_limit > 0) {
                        return true;
                    }
                }
            }
            return false;
        }
    }

    
    

    if (!function_exists('credit_balance')) {
        function credit_balance($user_id)
        {
            if($user_id > 0){
                $data = PayLater::where('user_id', $user_id)->first();
                if(is_payLater($user_id)){
                    $used_credit = Payment::where('user_id', $user_id)
                    ->where('status', '!=', 'completed')
                    ->where('payment_method', 2)
                    ->sum('pay_later_credit');
                    // dd($used_credit);
                    return $data->credit_limit - $used_credit;
                }
            }
            return 0;
        }

    }

    if(!function_exists('user_credit')) {
        function user_credit($user_id)
        {
            if(is_payLater($user_id)){
                return PayLater::where('user_id', $user_id)->first()->credit_limit;
            }
        }
        return 0;
    }
}
