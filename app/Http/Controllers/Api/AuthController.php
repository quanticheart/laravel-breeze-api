<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Erro ao validar dados',
                'success' => false,
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Usuário ou senha incorretos.',
                'success' => false,
            ]);

        } else {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response()->json([
                    'message' => 'Sucesso!',
                    'success' => true,
                    'token' => $user->createToken(md5($user->email) . $user->email)->plainTextToken,
                ]);
            }
        }
        return response()->json([
            'message' => 'Erro inesperado',
            'success' => false,
        ]);
    }
}
