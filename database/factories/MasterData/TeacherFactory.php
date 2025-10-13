<?php

namespace Database\Factories\MasterData;

use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\User;
use App\Models\MasterData\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterData\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'nip' => $this->faker->unique()->numerify(str_repeat('#', 18)),
            'name' => $this->faker->name(),
            'dob' => $this->faker->dateTimeBetween('-55 years', '-30 years'),
            'user_id' => User::factory()->teacher(),
        ];
    }

    public function withoutUser(): static
    {
        return $this->state(fn () => ['user_id' => null]);
    }
}
