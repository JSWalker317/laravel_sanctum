<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password, [])) {
            return response()->json(
                [
                    'message' =>"User not exist"
                ],404
            );
        }
        $token = $user->createToken('authToken')->plainTextToken;


        return response()->json(
            [
                'access_token' => $token, 
                'type_token' => 'Bearer'
            ],200
        );
    }

    public function register(Request $request){
        $messages = [
            'email.email' => 'Error email',
            'email.required' => 'Required email',
            'password.required' => 'Required password'
        ];

        $validate = Validator::make($request->all(),[
             'email'=> 'email|required',
             'password'=> 'required'
            ], $messages);
        if($validate->fails()){
            return response()->json(
                [
                    'message' => $validate->errors()
                ],404
            );
        }

        User::create([
            'name' => $request->name,
            'email' => $request-> email,
            'password' => Hash::make($request-> password)
        ]);

        return response()->json(
            [
                'message' =>"Created"
            ],200
        );
    }

    public function user(Request $request){
        return $request->user;
    }

    public function logout(){
        return 'Logout';
    }
}
