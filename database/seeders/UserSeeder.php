<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert roles
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'zeacut.admin@gmail.com',
            'phone' => '9946464646',
            'role_id' => 1, // Ensure that role_id exists in 'role' table
            'password' => Hash::make('zeacut.admin@gmail.com'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
