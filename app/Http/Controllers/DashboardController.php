<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
    public function getStudentDashboardData(Request $request): JsonResponse
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

                $savingsData = Savings::select(['id', 'amount'])->where('user_id', $user->id)->first();

                $totalSavingsInTransactions = SavingsHistory::where('savings_id', $savingsData->id)->where('type', 'in')->count('amount');
                $totalSavingsOutTransactions = SavingsHistory::where('savings_id', $savingsData->id)->where('type', 'out')->count('amount');

                $lastFiveInTransactions = SavingsHistory::where('savings_id', $savingsData->id)->where('type', 'in')->orderBy('created_at', 'desc')->limit(5)->get();
                $lastFiveOutTransactions = SavingsHistory::where('savings_id', $savingsData->id)->where('type', 'out')->orderBy('created_at', 'desc')->limit(5)->get();

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
            return response()->json([
                'status' => false,
                'message' => 'Forbidden'
            ], 403);
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }

    public function getSavingsStatistics(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            // Highest transactions
            $highestIn = SavingsHistory::where('user_id', $userId)->where('type', 'in')->orderBy('amount', 'desc')->first();
            $highestOut = SavingsHistory::where('user_id', $userId)->where('type', 'out')->orderBy('amount', 'desc')->first();

            // Lowest transactions
            $lowestIn = SavingsHistory::where('user_id', $userId)->where('type', 'in')->orderBy('amount', 'asc')->first();
            $lowestOut = SavingsHistory::where('user_id', $userId)->where('type', 'out')->orderBy('amount', 'asc')->first();

            // Averages
            $avgIn = SavingsHistory::where('user_id', $userId)->where('type', 'in')->avg('amount') ?? 0;
            $avgOut = SavingsHistory::where('user_id', $userId)->where('type', 'out')->avg('amount') ?? 0;

            // Monthly average in (group by month)
            $avgInMonthly = SavingsHistory::where('user_id', $userId)
                ->where('type', 'in')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, AVG(amount) as avg_amount')
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            $avgOutMonthly = SavingsHistory::where('user_id', $userId)
                ->where('type', 'out')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, AVG(amount) as avg_amount')
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Savings statistics retrieved successfully',
                'data' => [
                    'highest' => [
                        'in' => $highestIn,
                        'out' => $highestOut
                    ],
                    'lowest' => [
                        'in' => $lowestIn,
                        'out' => $lowestOut
                    ],
                    'average' => [
                        'in' => $avgIn,
                        'out' => $avgOut
                    ],
                    'monthly_average' => [
                        'in' => $avgInMonthly,
                        'out' => $avgOutMonthly
                    ]
                ]
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }

    public function getSavingsHistories(Request $request): JsonResponse
    {
        $user = $request->user();
        try {
            $savingsHistoryQuery = SavingsHistory::query()->where('savings_id', $user->savings->id)->orderBy('id', 'desc');

            // Search
            $allowedType = ['type', 'description'];
            $type = $request->query('type');

            if ($type && $type === 'date') {
                $startDate = $request->query('start_date');
                $endDate = $request->query('end_date');
                $savingsHistoryQuery->whereBetween('created_at', [$startDate, $endDate]);
            } else if ($type && in_array($type, $allowedType)) {
                $query = $request->query('query');
                $savingsHistoryQuery->where($type, 'like', '%' . $query . '%');
            }

            // Limit
            $limit = $request->query('limit', 10);
            if ($limit === 'all') {
                $savingsHistory = $savingsHistoryQuery->get();
            } else {
                $savingsHistory = $savingsHistoryQuery->paginate($limit);
            }
            return response()->json([
                'status' => true,
                'message' => 'Savings history retrieved successfully',
                'data' => $savingsHistory
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }
}
