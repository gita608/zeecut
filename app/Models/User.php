<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role_id',
        'place',
        'password',
        'address',
        'pincode',
        'notification_token'
    ];

    public function userdata()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'address' => $this->address,
            'pincode' => $this->pincode,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function generateToken()
    {
        return $this->createToken('authToken')->plainTextToken;
    }
}
