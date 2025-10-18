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
        $userId = User::where('role', 'student')->inRandomOrder()->first()->id;
        $savingsId = Savings::where('user_id', $userId)->first()->id;
        return [
            'savings_id' => $savingsId,
            'user_id' => $userId,
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'type' => $this->faker->randomElement(['in', 'in', 'in', 'out']),
            'description' => $this->faker->sentence(3),
        ];
    }
}
