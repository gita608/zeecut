<?php
use App\Models\Setting;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

if (!function_exists('getSessionValue')) {
    function getSessionValue($key)
    {
        return Session::get($key, null);
    }
}

if (!function_exists('getUserId')) {
    function getUserId()
    {
        return getSessionValue('user_id');
    }
}

if (!function_exists('getRoleId')) {
    function getRoleId()
    {
        return getSessionValue('role_id');
    }
}

if (!function_exists('getRoleName')) {
    function getRoleName()
    {
        return getSessionValue('role_name');
    }
}

if (!function_exists('getUserName')) {
    function getUserName()
    {
        return getSessionValue('user_name');
    }
}

if (!function_exists('getUserPhone')) {
    function getUserPhone()
    {
        return getSessionValue('user_phone');
    }
}

if (!function_exists('getUserEmail')) {
    function getUserEmail()
    {
        return getSessionValue('user_email');
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return getRoleId() == 1;
    }
}

if (!function_exists('isUser')) {
    function isUser()
    {
        return getRoleId() == 2;
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        return Auth::check();
    }
}

if (!function_exists('getGreetingMessage')) {
    function getGreetingMessage()
    {
        $hour = Carbon::now()->format('H');
        if ($hour >= 5 && $hour < 12) {
            return "Good Morning";
        } elseif ($hour >= 12 && $hour < 16) {
            return "Good Afternoon";
        } elseif ($hour >= 16 && $hour < 24) {
            return "Good Evening";
        } else {
            return "Good Night";
        }
    }
}

if (!function_exists('setUserSession')) {
    function setUserSession($userData)
    {
        Session::put($userData);
    }
}

if (!function_exists('destroySession')) {
    function destroySession()
    {
        Session::flush();
    }
}

if (!function_exists('hasRole')) {
    function hasRole($roleName)
    {
        return getRoleName() === $roleName;
    }
}

if (!function_exists('redirectIfNotLoggedIn')) {
    function redirectIfNotLoggedIn()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
    }
}

if (!function_exists('redirectIfLoggedIn')) {
    function redirectIfLoggedIn()
    {
        if (Auth::check()) {
            return redirect('/admin/dashboard');
        }
    }
}

if (!function_exists('sanitizeInput')) {
    function sanitizeInput($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('getBaseUrl')) {
    function getBaseUrl($path = '')
    {
        return url($path);
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('userHasPermission')) {
    function userHasPermission($permission)
    {
        $permissions = Session::get('permissions', []);
        return in_array($permission, $permissions);
    }
}

if (!function_exists('getCountryCode')) {
    function getCountryCode()
    {
        return [
            '91' => 'INDIA',
            '1' => 'UNITED STATES',
            '358' => 'FINLAND',
            '33' => 'FRANCE',
            '49' => 'GERMANY',
            '61' => 'AUSTRALIA',
            '353' => 'IRELAND',
            '39' => 'ITALY',
            '965' => 'KUWAIT',
            '370' => 'LITHUANIA',
            '64' => 'NEW ZEALAND',
            '968' => 'OMAN',
            '48' => 'POLAND',
            '974' => 'QATAR',
            '966' => 'SAUDI ARABIA',
            '34' => 'SPAIN',
            '46' => 'SWEDEN',
            '971' => 'UNITED ARAB EMIRATES',
            '44' => 'UNITED KINGDOM',
        ];
    }
}

if (!function_exists('format_price')) {
    function format_price($amount)
    {
        return '₹ ' . number_format($amount);
    }
}