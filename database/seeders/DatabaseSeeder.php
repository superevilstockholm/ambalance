<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\User;
use App\Models\MasterData\Teacher;
use App\Models\MasterData\Classes;
use App\Models\MasterData\Student;
use App\Models\Savings\SavingsHistory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Role - dibuat hanya jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'password' => Hash::make('Password#123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Teachers With User
        Teacher::factory(10)->create();
        // Teachers Without User
        Teacher::factory(10)->withoutUser()->create();

        // Classes
        Classes::factory(10)->create();

        // Students With User
        Student::factory(10)->create();
        // Students Without User
        Student::firstOrCreate(
            [
                'nisn' => '0012345678',
            ],[
            'name' => 'Hillary Aimee Srijaya',
            'dob' => '2008-06-23',
            'class_id' => Classes::inRandomOrder()->first()->id,
        ]);
        Student::factory(10)->withoutUser()->create();

        // Savings History
        SavingsHistory::factory(300)->create();
    }
}
