<?php

namespace Vitacode\ModuleUsersSystem\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{


    protected string $userModel;

    public function __construct()
    {
        $this->userModel = config('users_system.user_model', \App\Models\User::class);
    }

    public function login(Request $request)
    {
        $visible = config('users_system.user_visible_attributes', ['id', 'name', 'email']);
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = $this->userModel::where('email', $data['email'])->first(array_merge($visible, ['password']));
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->responseCustom(true, [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 'Login exitoso', Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseCustom(true, [], 'Logout exitoso', 200);
    }


    private function responseCustom(Bool $status = true, $data = [], String $message = 'Acción procesada con éxito.', Int $code = Response::HTTP_OK)
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ], $code);
    }
}