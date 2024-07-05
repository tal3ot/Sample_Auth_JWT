<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //register api (Post) it will pass the data inside formdata (we don't need token)
    public function register(Request $request)
    {
        //Data Validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users', 
            'password' => 'required|confirmed',
        ]);

        //Data save
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Response
        return response()->json([
            'Status' => true,
            'Message' => "User Created Successfully",
        ]);
    }

    //login api (Post) it will pass email and password inside formdata (we don't need token, we generate token)
    public function login(Request $request)
    {
        //Data Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //JWTAuth and attmpt, it generate a new token for each email by the attempt fn
        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        //Response
        if (!empty($token)) {
            return response()->json([
                'Status' => true,
                'Message' => "User Logged in Successfully",
                'token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]);
        }
        return response()->json([
            'Status' => false,
            'Message' => "Invalid Login details",
        ]);
    }

    // profile api (GET), we need to pass authorization token value which will be JWT 
    public function profile()
    {
        $userData = auth()->user();

        return response()->json([
            "status" => true,
            'message' => "Profile Data",
            "user" => $userData,
        ]);
    }

    // refresh api (GET), it will refresh the token 
    public function refreshToken()
    {
        $newToken = auth()->refresh();
        
        return response()->json([
            "status" => true,
            'message' => "New Access Token Generated",
            "token" => $newToken,
        ]);
    }

    // logout api (GET), full log out and destroy the old token 
    public function logout()
    {
        auth()->logout();

        return response()->json([
            "status" => true,
            'message' => "User Logged Out Successfully",
        ]);
    }
}
