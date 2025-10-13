<?php

namespace Database\Factories\MasterData;

use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\MasterData\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterData\Classes>
 */
class ClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_name' => $this->faker->unique()->randomElement([
                'X RPL 1', 'X RPL 2', 'X RPL 3', 'XI RPL 1', 'XI RPL 2', 'XI RPL 3', 'XII RPL 1', 'XII RPL 2', 'XII RPL 3',
                'X TKJ 1', 'X TKJ 2', 'XI TKJ 1', 'XI TKJ 2', 'XI TKJ 3', 'XII TKJ 1', 'XII TKJ 2', 'XII TKJ 3',
                'X MM 1', 'X MM 2', 'X MM 3', 'XI MM 1', 'XI MM 2', 'XII MM 1', 'XII MM 2', 'X DKV 1', 'XI DKV 2'
            ]),
            'description' => $this->faker->sentence(8),
            'teacher_id' => $this->getRandomTeacherId(),
        ];
    }

    protected function getRandomTeacherId(): ?int
    {
        $teacher = Teacher::inRandomOrder()->first();
        return $teacher?->id;
    }

    public function withoutTeacher(): static
    {
        return $this->state(fn () => ['teacher_id' => null]);
    }
}
