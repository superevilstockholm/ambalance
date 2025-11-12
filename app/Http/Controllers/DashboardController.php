<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// Models
use App\Models\Savings\Savings;
use App\Models\MasterData\Student;
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
            $savings = Savings::where('user_id', $user->id)->firstOrFail();
            $now = Carbon::now();
            $sixMonthsAgo = $now->copy()->subMonths(6)->startOfMonth();
            $twentyFourWeeksAgo = $now->copy()->subWeeks(23)->startOfWeek(Carbon::MONDAY);
            $totalIn = SavingsHistory::where('savings_id', $savings->id)
                ->where('type', 'in')
                ->selectRaw('COUNT(*) as count, SUM(amount) as total')
                ->first();
            $totalOut = SavingsHistory::where('savings_id', $savings->id)
                ->where('type', 'out')
                ->selectRaw('COUNT(*) as count, SUM(amount) as total')
                ->first();
            $weeklyQuery = SavingsHistory::where('savings_id', $savings->id)
                ->whereBetween('created_at', [$twentyFourWeeksAgo, $now])
                ->selectRaw("YEARWEEK(created_at, 3) as week_key, COUNT(*) as count, SUM(amount) as total")
                ->groupBy('week_key')
                ->get()
                ->keyBy('week_key');
            $weeklyPeriod = collect(CarbonPeriod::create($twentyFourWeeksAgo, '1 week', $now))
                ->map(fn($d) => $d->copy()->startOfWeek(Carbon::MONDAY))
                ->unique(fn($d) => $d->format('oW'))
                ->sortByDesc(fn($d) => $d)
                ->values()
                ->take(24);
            $weeklyGrowth = ['count' => [], 'amount' => []];
            foreach ($weeklyPeriod as $date) {
                $weekKey = $date->format('oW');
                $weeklyGrowth['count'][] = (int) ($weeklyQuery[$weekKey]->count ?? 0);
                $weeklyGrowth['amount'][] = number_format($weeklyQuery[$weekKey]->total ?? 0, 2, '.', '');
            }
            $monthlyQuery = SavingsHistory::where('savings_id', $savings->id)
                ->whereBetween('created_at', [$sixMonthsAgo, $now])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as count, SUM(amount) as total")
                ->groupBy('month_key')
                ->get()
                ->keyBy('month_key');
            $monthlyPeriod = collect(CarbonPeriod::create($sixMonthsAgo, '1 month', $now))
                ->map(fn($d) => $d->copy()->startOfMonth())
                ->unique(fn($d) => $d->format('Y-m'))
                ->sortByDesc(fn($d) => $d)
                ->values()
                ->take(6);
            $monthlyGrowth = ['count' => [], 'amount' => []];
            foreach ($monthlyPeriod as $date) {
                $monthKey = $date->format('Y-m');
                $monthlyGrowth['count'][] = (int) ($monthlyQuery[$monthKey]->count ?? 0);
                $monthlyGrowth['amount'][] = number_format($monthlyQuery[$monthKey]->total ?? 0, 2, '.', '');
            }
            $firstSavingDate = SavingsHistory::where('savings_id', $savings->id)
                ->orderBy('created_at', 'asc')
                ->value('created_at');
            if ($firstSavingDate) {
                $firstWeekStart = Carbon::parse($firstSavingDate)->startOfWeek(Carbon::MONDAY);
                $weeksSinceStart = $firstWeekStart->diffInWeeks($now) + 1;
            } else {
                $weeksSinceStart = 0;
            }
            return response()->json([
                'status' => true,
                'data' => [
                    'total_transactions' => [
                        'in' => (int) ($totalIn->count ?? 0),
                        'out' => (int) ($totalOut->count ?? 0),
                    ],
                    'total_value' => [
                        'in' => number_format($totalIn->total ?? 0, 2, '.', ''),
                        'out' => number_format($totalOut->total ?? 0, 2, '.', ''),
                    ],
                    'growth' => [
                        'weekly' => $weeklyGrowth,
                        'monthly' => $monthlyGrowth,
                    ],
                    'current_balance' => number_format($savings->amount, 2, '.', ''),
                    'weeks_since_start' => $weeksSinceStart,
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
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
