<?php

namespace Database\Factories\MasterData;

use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\User;
use App\Models\MasterData\Student;
use App\Models\MasterData\Classes;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterData\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'nisn' => $this->faker->unique()->numerify('00########'),
            'name' => $this->faker->name(),
            'dob' => $this->faker->dateTimeBetween('-19 years', '-15 years'),
            'class_id' => $this->getRandomClassId(),
            'user_id' => User::factory()->student()
        ];
    }

    protected function getRandomClassId(): ?int
    {
        $class = Classes::inRandomOrder()->first();
        return $class?->id;
    }

    protected function getRandomStudentUserId(): ?int
    {
        $user = User::where('role', 'student')->inRandomOrder()->first();
        return $user?->id;
    }

    public function withoutUser(): static
    {
        return $this->state(fn () => ['user_id' => null]);
    }

    public function withoutClass(): static
    {
        return $this->state(fn () => ['class_id' => null]);
    }
}
