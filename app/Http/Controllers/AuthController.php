<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;



class AuthController extends Controller
{
    //
    public function  register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role'=>'required',
            'password' => 'required',
        ]);
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'role' => $request->role,
            'password'=>Hash::make($request->password),
        ]);
        try{
            $token=JWTAuth::fromUser($user);//creer un token directement a partir dun user (le token contient user_id)
        }catch(JWTException $e){
            return response()->json(['erreur'=>'probleme dans token'],500);
        }
        return response()->json([
            'token'=>$token,
            'user'=>$user,
        ],201);
    }
    public function login(Request $request){
        $credentials =$request->only('email','password');//only => recupere des champs sepecifique pas tout
        try{
            if (!$token=JWTAuth::attempt($credentials)){//attempt => virifier les identidfiants => creer un token
                return response()->json([
                    'erreur'=>'credentials invalide',
                ],401);
            }
        }catch(JWTException $e){
            return response()->json([
                    'erreur'=>'problem dans token'
                ],500);
        }

        return response()->json([
            'token'=>$token,
            'expires_id'=>auth('api')->factory()->getTTL()*60,
        ]);
    }
    public function logout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
        }catch(JWTException $e){
            return response()->json([
                'erreur'=>'failed to logout'
            ]);
        }
        return response()->json([
                'erreur'=>'successfully  logout'
            ]);
    }
}
