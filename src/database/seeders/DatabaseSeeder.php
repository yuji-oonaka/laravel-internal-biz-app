<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Request as WorkRequest;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // もしユーザーがいなければ作成（テスト用）
        if (User::count() === 0) {
            $this->call(UserSeeder::class); // 以前作ったSeederを呼ぶ
        }

        // 申請データを20件生成
        WorkRequest::factory()->count(20)->create();
    }
}
