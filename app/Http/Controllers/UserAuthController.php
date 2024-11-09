<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function login(Request $request){
        try{
            //Validation rules
            $rules=[
                "email"=>"required|email",
                "password"=>"required|min:6|max:12",
            ];

            $validation= Validator::make($request->all(),$rules);

            if($validation->fails()){
                return response()->json([
                    'errors' => $validation->errors(),
                ], 422);
            }

            //Check user
            $user= User::where('email',$request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['error' => 'Invalid credentials.']);
            }

            //Generate token
            $token= $user->createToken('todo-api')->plainTextToken;

            return response()->json([
                'Message' => "Login Successful.",
                'Email'=> $user->email,
                'Token'=> $token,
            ], 200);


        }catch(Exception $e){
            return response()->json(['error' => 'Failed to login.'], 500);
        }

    }

    public function signup(Request $request){
        try{
            $rules=[
                "name"=>"required|max:255|string",
                "email"=>"required|email|unique:users,email",
                "password"=>"required|min:6|max:12",
            ];

            $validation= Validator::make($request->all(),$rules);

            if($validation->fails()){
                return response()->json([
                    'errors' => $validation->errors(),
                ], 422);
            }

            $user=User::create([
                "email"=>$request->email,
                "name"=>$request->name,
                "password"=>bcrypt($request->password),

            ]);


            return response()->json([
                'Message' => "Registration Successful",
                'Email'=> $user->email,
            ], 201);


        }catch(Exception $e){
            return response()->json(['error' => 'Failed to register.'], 500);
        }

    }

    public function logout(Request $request)
    {
    try {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully.'], 200);
    } catch (Exception $e) {
        return response()->json(['error' => 'Failed to log out.'], 500);
    }
    }
}
