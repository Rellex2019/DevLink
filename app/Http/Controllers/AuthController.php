<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);



            // Можно добавить отправку email для верификации
            // $user->sendEmailVerificationNotification();
            Auth::login($user);

            DB::commit();

            return response()->json([
                'message' => 'Регистрация прошла успешно',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->profile->avatar,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Произошла ошибка при регистрации',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->load('profile');

            return response()->json([
                'message' => 'Вы успешно вошли',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->profile->avatar ?? null,
                ]
            ]);
        }

        return response()->json([
            'message' => 'Предоставленные учетные данные неверны.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Вы вышли из аккаунта']);
    }
}
