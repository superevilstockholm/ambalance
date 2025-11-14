<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// Models
use App\Models\MasterData\Teacher;
use App\Models\MasterData\Classes;

class TeacherDashboardController extends Controller
{
    public function getTeacherDashboardData(Request $request): JsonResponse
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
            $teacher = Teacher::select(['id', 'nip', 'name'])->where('user_id', $user->id)->first();
            $teacherData = [
                'nisn' => $teacher->nisn,
                'name' => $teacher->name
            ];
            $kelasData = Classes::select(['class_name', 'description'])->where('teacher_id', $teacher->id)->get();
            return response()->json([
                'status' => true,
                'message' => 'Get teacher dashboard data successfully',
                'data' => [
                    'user' => $userData,
                    'teacher' => $teacherData,
                    'kelas' => $kelasData
                ]
            ]);
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
