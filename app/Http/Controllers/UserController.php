<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $response['status'] = 0;
            $response['message'] = "Email Already Exists";
            $response['code'] = 409;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $response['status'] = 1;
            $response['message'] = "User Register Successfully";
            $response['code'] = 200;
        }
        return response()->json($response);
    }

    public function login(Request $request)
    {
        $credentials=$request->only('email','password');
        try{
            if(!JWTAuth::attempt($credentials)){
            $response ['status']=0;
            $response ['code']=401;
            $response ['message']="Email or Password Incorrect !!";
            return response()->json($response);
        }

        }catch(JWTException $e){
            $response ['data']=null;
            $response ['code']=500;
            $response ['message']="could not create tokken !!";
        }
        $user = auth()->user();

        // $data['tokken']=auth()->claims(['user_id'=>$user->id, 'email'=>$user->email])->attempt($credentials);
        // $user = auth()->user();
        // $data['token']= auth()->claims([
        // 'user_id' => $user->id,
        // 'email' => $user->email
        // ])->attempt($credentials);


        // return $response;
        // $response['data']= $data;
        $response ['status']=1;
        $response ['code']=200;
        $response ['message']="Login Successfully !!";
        return response()->json($response);
    }
}
