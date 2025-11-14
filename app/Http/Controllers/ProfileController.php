<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

// Models
use App\Models\User;
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
                $user->load('teacher.classes');
                return response()->json([
                    'status' => true,
                    'message' => 'Get user profile successfully',
                    'data' => $user
                ], 200);
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

    public function editUserProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string|in:profile_picture,email',
            ]);
            $user = $request->user();
            if ($validated['type'] === 'profile_picture') {
                $validated = $request->validate([
                    'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('users/profile_pictures', 'public');
                User::where('id', $user->id)->update([
                    'profile_picture' => $path
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Update profile picture successfully',
                ], 200);
            } else if ($validated['type'] === 'email') {
                $validated = $request->validate([
                    'email' => 'required|email|unique:users,email,' . $user->id
                ]);
                User::where('id', $user->id)->update([
                    'email' => $validated['email'],
                    'email_verified_at' => null
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Update email successfully'
                ], 200);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 400);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
