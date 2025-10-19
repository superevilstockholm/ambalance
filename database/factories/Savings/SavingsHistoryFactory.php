<?php

namespace Database\Factories\Savings;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\User;

use App\Models\MasterData\Student;
use App\Models\MasterData\Teacher;
use App\Models\MasterData\Classes;

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
        while (true) {
            // Choose random student user
            $studentUser = User::where('role', 'student')->inRandomOrder()->first();
            // Get student from student user
            $student = Student::where('user_id', $studentUser->id)->with('class')->first();
            // Get teacher from student class
            $teacher = Teacher::where('id', $student->class->teacher_id)->with('user')->first();

            // If teacher does not have user, skip
            if (empty($teacher->user_id) || empty($teacher->user) || empty($teacher->user->id)) {
                continue;
            }
            // If all conditions are met, break the loop
            break;
        }

        $studentSavings = Savings::where('user_id', $studentUser->id)->first();

        $randomCreatedAt = now()->subMonths(rand(0, 5))->subDays(rand(0, 30))->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

        return [
            'savings_id' => $studentSavings->id,
            'user_id' => $teacher->user->id, // id user teacher yang membuat transaksi
            'amount' => $this->faker->numberBetween(2, 200) * 500,
            'type' => $this->faker->randomElement(['in', 'in', 'in', 'out']),
            'description' => $this->faker->sentence(3),
            'created_at' => $randomCreatedAt,
            'updated_at' => $randomCreatedAt
        ];
    }
}
