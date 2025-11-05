<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// Models
use App\Models\MasterData\Teacher;
use App\Models\MasterData\Student;

class ProfileController extends Controller
{
    public function getUser(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $fullname = null;
            if ($user->role === 'student') {
                $fullname = Student::select('name')->where('user_id', $user->id)->first()->name;
            } else if ($user->role === 'teacher') {
                $fullname = Teacher::select('name')->where('user_id', $user->id)->first()->name;
            }
            return response()->json([
                'status' => true,
                'message' => 'Get user successfully',
                'data' => [
                    'fullname' => $fullname,
                    'role' => $user->role,
                    'profile_picture_url' => $user->profile_picture_url
                ]
            ], 200);
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            if ($user->role === 'student') {
                $user->load('student.class');
                return response()->json([
                    'status' => true,
                    'message' => 'Get user profile successfully',
                    'data' => $user
                ], 200);
            } else if ($user->role === 'teacher') {

            } else if ($user->role === 'admin') {

            }
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
