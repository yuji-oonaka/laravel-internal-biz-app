<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者
        User::create([
            'name' => '管理者 太郎',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        // 一般社員
        foreach (range(1, 3) as $i) {
            User::create([
                'name' => "一般社員 {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'is_active' => true,
            ]);
        }
    }
}
