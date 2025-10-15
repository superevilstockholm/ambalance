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
            if ($user->role === 'student') {
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
                $totalSavingsInTransactions = SavingsHistory::where('user_id', $user->id)->where('type', 'in')->count('amount');
                $totalSavingsOutTransactions = SavingsHistory::where('user_id', $user->id)->where('type', 'out')->count('amount');

                $lastFiveInTransactions = SavingsHistory::where('user_id', $user->id)->where('type', 'in')->orderBy('created_at', 'desc')->limit(5)->get();
                $lastFiveOutTransactions = SavingsHistory::where('user_id', $user->id)->where('type', 'out')->orderBy('created_at', 'desc')->limit(5)->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Get student dashboard data successfully',
                    'data' => [
                        'user' => $userData,
                        'student' => $studentData,
                        'kelas' => $kelasData,
                        'savings' => [
                            'amount' => number_format($savingsData->amount, 0, ',', '.'),
                            'total_in_transactions' => $totalSavingsInTransactions,
                            'total_out_transactions' => $totalSavingsOutTransactions
                        ],
                        'last_transactions' => [
                            'in' => $lastFiveInTransactions,
                            'out' => $lastFiveOutTransactions
                        ]
                    ]
                ], 200);
            }
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }
}
