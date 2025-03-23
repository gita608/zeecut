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
    ];

    public function userdata()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'token' => $this->generateToken(),
        ];
    }

    private function generateToken()
    {
        return $this->createToken('authToken')->plainTextToken;
    }
}
