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
        $all_users = [
            [
                'name' => 'Admin',
                'account_type' => 'INDIVIDUAL',
                'balance' => 50000,
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Individual User',
                'account_type' => 'INDIVIDUAL',
                'balance' => 50000,
                'email' => 'individual@gmail.com',
                'password' => Hash::make('password')
            ],
            [
                'name' => 'Business User',
                'account_type' => 'BUSINESS',
                'balance' => 100000,
                'email' => 'business@gmail.com',
                'password' => Hash::make('password')
            ]
        ];
        foreach ($all_users as $all_user) {
            User::create($all_user);
        }
    }
}
