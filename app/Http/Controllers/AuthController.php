<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // mencari user = apakah user yang di input ada atau tidak
        $user = User::where('email', $request->email)->first();


        // mengecek apakah password yang di input dengan password yang ada di database sama
        // jika passowrd yang di inputkan oleh user tidak sama dengan password yang di databasee
        // maka throw error
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login Success',
            'token' => $user->createToken('user_login')->plainTextToken,
        ], 200);
    }

    // Revoke token / hapus token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout Success'], 200);
    }

    // function ini untuk mengambil data user  yang sedah login
    public function me (Request $request){

        // return response()->json($request->user());
        // atau seperti ini
        return response()->json(Auth::user());
    }

}
