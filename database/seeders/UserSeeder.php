<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $data = [
            [
                'role' => 'admin',
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
            ],
            [
                'role' => 'user',
                'name' => 'user',
                'email' => 'user@user.com',
                'password' => Hash::make('password'),
            ]
        ];
        User::insert($data);
    }
}
