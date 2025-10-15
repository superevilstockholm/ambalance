<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;

// Models
use App\Models\User;
use App\Models\MasterData\Classes;

// Role
use App\Models\MasterData\Student;
use App\Models\MasterData\Teacher;

// Savings
use App\Models\Savings\Savings;
use App\Models\Savings\SavingsHistory;

class DashboardController extends Controller
{
    public function getStudentDashboardData(Request $request)
    {
        try {
            $user = $request->user();
            $userData = [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'profile_picture' => $user->profile_picture,
                'profile_picture_url' => $user->profile_picture_url
            ];
            $student = Student::select(['nisn', 'name', 'class_id'])->where('user_id', $user->id)->first();
            $studentData = [
                'nisn' => $student->nisn,
                'name' => $student->name
            ];
            $kelasData = $student->class()->select('class_name')->first()->class_name;
            $savingsData = Savings::select(['amount'])->where('user_id', $user->id)->first();
            $totalSavingsInTransaction = SavingsHistory::where('user_id', $user->id)->where('type', 'in')->sum('amount');
            $totalSavingsOutTransaction = SavingsHistory::where('user_id', $user->id)->where('type', 'out')->sum('amount');

            return response()->json([
                'status' => true,
                'message' => 'Get student dashboard data successfully',
                'data' => [
                    'user' => $userData,
                    'student' => $studentData,
                    'kelas' => $kelasData,
                    'savings' => [
                        'amount' => $savingsData->amount,
                        'total_in_transaction' => $totalSavingsInTransaction,
                        'total_out_transaction' => $totalSavingsOutTransaction
                    ]
                ]
            ], 200);
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }
}
