<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(SignupRequest $request){



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);

    }

    public function login(LoginRequest $request){



        if(Auth::attempt(['email'=> $request->email , 'password'=> $request->password])){

            $user = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'data' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);



        }

        else{
            return response()->json([
                'status' => false,
                'message' => 'Authentication Failed',

            ], 401);
        }

    }

    public function logout(Request $request){


     $user =request()->user();
     $user->tokens()->delete();
     return response()->json([
         'status' => true,
         'message' => 'User logged out successfully',

     ], 200);
    }
}
