<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            // ランダムなユーザーID（既存のユーザーから取得）
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'title'   => $this->faker->realText(20) . 'の申請',
            'content' => $this->faker->realText(100),
            // ランダムなステータスを割り当て
            'status'  => $this->faker->randomElement(RequestStatus::cases()),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
