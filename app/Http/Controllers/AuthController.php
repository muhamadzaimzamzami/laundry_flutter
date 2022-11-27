<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    //Register User
    public function register(Request $request){
        //validate field
        $attrs = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:6|confirmed'

        ]);

        //created users
        $user = User::create([
            'name'=>$attrs['name'],
            'email'=>$attrs['email'],
            'password'=>bcrypt($attrs['password']),
        ]);

        //return user and token in respon
        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken
        ], 200);

    }
    //Login User
    public function login(Request $request){
        //validate field
        $attrs = $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|min:6'

        ]);

        //attempt login
        if (!Auth::attempt($attrs)) {
            return response([
                'message'=>'Invalid credentials.'
            ], 403);
        }

        //return user and token in respon
        return response([
            'user' => auth()->user(),
            'token'=> auth()->user()->createToken('secret')->plainTextToken
        ], 200);

    }

    //logout user
    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success.'
        ], 200);
    }

    //get users details
    public function user(){
        return response([
            'user' => auth()->user()
        ], 200);
    }

    //update users
    public function update(Request $request){
        $attrs = $request->validate([
            'name'=>'required|string'
        ]);

        auth()->user()->update([
            'name' => $attrs['name'],
        ]);

        return response([
            'message' => 'User Updated',
            'user' => auth()->user()
        ], 200);
    }
}
