<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            return response()->json([
                'status' => false,
                'message' => 'Forbidden'
            ], 403);
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSavingsStatistics(Request $request)
    {
        try {
            $user = $request->user();
            $savingsData = Savings::select(['id', 'amount'])
                ->where('user_id', $user->id)
                ->firstOrFail();

            $now = Carbon::now();
            $sixMonthsAgo = $now->copy()->subMonths(6);
            $twelveWeeksAgo = $now->copy()->subWeeks(12);

            $totalSavingsInTransactions = SavingsHistory::where('savings_id', $savingsData->id)
                ->where('type', 'in')
                ->count();
            $totalSavingsOutTransactions = SavingsHistory::where('savings_id', $savingsData->id)
                ->where('type', 'out')
                ->count();

            $totalValueSavingsInTransactions = SavingsHistory::where('savings_id', $savingsData->id)
                ->where('type', 'in')
                ->sum('amount');
            $totalValueSavingsOutTransactions = SavingsHistory::where('savings_id', $savingsData->id)
                ->where('type', 'out')
                ->sum('amount');

            $weeklyData = SavingsHistory::where('savings_id', $savingsData->id)
                ->whereBetween('created_at', [$twelveWeeksAgo, $now])
                ->selectRaw('YEARWEEK(created_at, 1) as week, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('week')
                ->orderBy('week', 'desc')
                ->limit(12)
                ->get()
                ->reverse()
                ->values();

            $weeklyGrowth = [
                'count' => $weeklyData->pluck('count')->toArray(),
                'amount' => $weeklyData->pluck('total')->toArray(),
            ];

            $monthlyData = SavingsHistory::where('savings_id', $savingsData->id)
                ->whereBetween('created_at', [$sixMonthsAgo, $now])
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
                ->reverse()
                ->values();

            $monthlyGrowth = [
                'count' => $monthlyData->pluck('count')->toArray(),
                'amount' => $monthlyData->pluck('total')->toArray(),
            ];

            return response()->json([
                'status' => true,
                'data' => [
                    'total_transactions' => [
                        'in' => $totalSavingsInTransactions,
                        'out' => $totalSavingsOutTransactions,
                    ],
                    'total_value' => [
                        'in' => $totalValueSavingsInTransactions,
                        'out' => $totalValueSavingsOutTransactions,
                    ],
                    'growth' => [
                        'weekly' => $weeklyGrowth,
                        'monthly' => $monthlyGrowth,
                    ],
                    'current_balance' => $savingsData->amount,
                ]
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSavingsHistories(Request $request): JsonResponse
    {
        $user = $request->user();
        try {
            $savingsHistoryQuery = SavingsHistory::query()->where('savings_id', $user->savings->id)->orderBy('id', 'desc')->with([
                'user.teacher' => function ($query) {
                    $query->select('id', 'user_id', 'name');
                }
            ]);

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

            $savingsHistory->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'savings_id' => $item->savings_id,
                    'amount' => $item->amount,
                    'type' => $item->type,
                    'description' => $item->description,
                    'created_at' => $item->created_at,
                    'teacher' => $item->user && $item->user->teacher
                        ? [
                            'id' => $item->user->teacher->id,
                            'name' => $item->user->teacher->name,
                        ]
                        : null,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Savings history retrieved successfully',
                'data' => $savingsHistory
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
