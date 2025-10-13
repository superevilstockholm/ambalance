<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Models
use App\Models\User;
use App\Models\MasterData\Teacher;
use App\Models\MasterData\Student;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nisn' => 'nullable|string|min:10|max:10',
                'nip' => 'nullable|string|min:18|max:18',
                'dob' => 'required|date',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&\-_]).+$/'
                ]
            ], [
                'password.regex' => 'Password must contain at least one lowercase letter a-z, one uppercase letter A-Z, one digit 0-9, and one special character ! @ # $ % & - _.'
            ]);
            $validated['nisn'] = $validated['nisn'] ?? null;
            $validated['nip'] = $validated['nip'] ?? null;
            // Empty nisn and nip
            if (empty($validated['nisn']) && empty($validated['nip'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'NISN/NIP is required'
                ], 400)->withoutCookie('auth_token', '/');
            }
            // Double data nisn and nip
            if (!empty($validated['nisn']) && !empty($validated['nip'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please provide only one identifier (NISN for students or NIP for teachers).'
                ], 400)->withoutCookie('auth_token', '/');
            }
            $userModel = null;
            if (!empty($validated['nisn'])) {
                $userModel = Student::where('nisn', $validated['nisn'])->first();
            } else if (!empty($validated['nip'])) {
                $userModel = Teacher::where('nip', $validated['nip'])->first();
            }
            if (!$userModel || empty($userModel)) {
                return response()->json([
                    'status' => false,
                    'message' => (!empty($validated['nisn']) ? 'Student' : 'Teacher') . ' not found'
                ], 404)->withoutCookie('auth_token', '/');
            }
            if ($userModel->user_id && User::where('id', $userModel->user_id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User already registered'
                ], 400)->withoutCookie('auth_token', '/');
            }
            // DOB check
            if (Carbon::parse($validated['dob'])->toDateString() !== Carbon::parse($userModel->dob)->toDateString()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Date of birth does not match'
                ], 401)->withoutCookie('auth_token', '/');
            }
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => empty($validated['nisn']) ? 'teacher' : 'student',
            ]);
            $userModel->user_id = $user->id;
            $userModel->save();
            return response()->json([
                'status' => true,
                'message' => 'Registered successfully'
            ], 201)->withoutCookie('auth_token', '/');
        } catch (ValidationException $e) {
            // Validation error
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 422)->withoutCookie('auth_token', '/');
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nisn' => 'nullable|string|min:10|max:10',
                'nip' => 'nullable|string|min:18|max:18',
                'password' => 'required|string|min:8|max:255'
            ]);
            $validated['nisn'] = $validated['nisn'] ?? null;
            $validated['nip'] = $validated['nip'] ?? null;
            // Empty nisn and nip
            if (empty($validated['nisn']) && empty($validated['nip'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'NISN/NIP is required'
                ], 400)->withoutCookie('auth_token', '/');
            // Double data nisn and nip
            } else if (!empty($validated['nisn']) && !empty($validated['nip'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please provide only one identifier (NISN for students or NIP for teachers).'
                ], 400)->withoutCookie('auth_token', '/');
            }
            // Get student / teacher
            $userModel = null;
            if (!empty($validated['nisn'])) {
                $userModel = Student::where('nisn', $validated['nisn'])->first();
            } else if (!empty($validated['nip'])) {
                $userModel = Teacher::where('nip', $validated['nip'])->first();
            }
            // Get user
            $user = User::where('id', $userModel->user_id)->first();
            if (!$userModel || !$user) {
                return response()->json([
                    'status' => false,
                    'message' => ($validated['nisn'] ? 'NISN' : 'NIP') . ' or password is incorrect'
                ], 404)->withoutCookie('auth_token', '/');
            }
            // Check password
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => ($validated['nisn'] ? 'NISN' : 'NIP') . ' or password is incorrect'
                ], 401)->withoutCookie('auth_token', '/');
            }
            // Delete all old token and create a new one
            $user->tokens()->delete();
            $tokenResult = $user->createToken('auth_token');
            $token = $tokenResult->accessToken;
            $token->expires_at = Carbon::now()->addDays(7); // 7 days
            $token->save();
            $plainToken = $tokenResult->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Login successfully',
            ], 200, ['x-user-role' => $user->role])->cookie(
                'auth_token', // name
                $plainToken, // value
                60 * 24 * 7, // expiration in minutes - 7 days
                '/', // path
                null, // domain
                false, // secure
                false, // httponly
            );
        } catch (ValidationException $e) {
            // Validation error
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 422)->withoutCookie('auth_token', '/');
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Logout successfully'
            ], 200)->withoutCookie('auth_token', '/');
        } catch (Throwable $e) {
            // Internal server error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500)->withoutCookie('auth_token', '/');
        }
    }
}
