<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();

        return response()->json($user, 201);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        // Attempt to authenticate the user
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Retrieve the authenticated user
        $user = auth('api')->user();

        // Return the token and user information
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // Token expiration time in seconds
            'user' => $user // Include user information
        ]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken();
            \Log::info('Current Token:', ['token' => $token]); // Log du token reçu
            $refreshedToken = $token->refresh();
            \Log::info('Refreshed Token:', ['refreshed_token' => $refreshedToken]); // Log du token rafraîchi

            return $this->respondWithToken($refreshedToken);
        } catch (JWTException $e) {
            \Log::error('Token refresh failed: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Token not provided or invalid'], 400);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token, // Changer de 'refresh_token' à 'access_token'
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'message' => 'token refreshed successfully'
        ]);
    }
}
