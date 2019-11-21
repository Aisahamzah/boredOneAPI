<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
   public function register(Request $request)
   {
        $validateData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required',
            'password' => 'required|confirmed'
        ]);  // this used to validate all the input to meet the requirements

        $validateData['password'] = bcrypt($request->password); //hash the password
        $user = User::create($validateData); //after the data is validate then we create the data
        $accessToken = $user->createToken('authToken')->accessToken; //this used to create access token
        return response(['user' =>$user, 'access_token'=>$accessToken]); //and returned the user and access token
   }

   public function login(Request $request)
   {
    $loginData = $request->validate([
        'email' => 'email|required',
        'password' => 'required'
    ]);

    if(!auth()->attempt($loginData)) {
        return response(['message'=>'Invalid credentials']);
    }
        $accessToken = auth()->user()->createToken('authToken')->accessToken; 
        return response(['user' => auth()->user(), 'access_token'=>$accessToken]);
   }
}
