<?php

namespace Database\Factories\Savings;

use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\User;
use App\Models\Savings\Savings;
use App\Models\Savings\SavingsHistory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Savings\SavingsHistory>
 */
class SavingsHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = SavingsHistory::class;

    public function definition(): array
    {
        // Ambil random user & savings yang sudah ada (atau buat dummy)
        $userId = User::inRandomOrder()->first()->id ?? 1;
        $savingsId = Savings::where('user_id', $userId)->inRandomOrder()->first()->id ?? 1;

        return [
            'savings_id' => $savingsId,
            'user_id' => $userId,
            'amount' => $this->faker->numberBetween(1000, 500000),
            'type' => $this->faker->randomElement(['in', 'out']),
            'description' => $this->faker->sentence(3),
        ];
    }
}
