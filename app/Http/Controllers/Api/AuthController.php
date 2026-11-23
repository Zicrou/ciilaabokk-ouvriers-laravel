<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\LoginFormRequest;
use App\Http\Requests\api\v1\RegisterFormRequest;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Team;

class AuthController extends Controller 
{
    public function register(RegisterFormRequest $request){
        $fields = $request->validated();

        $fields['password'] =  Hash::make($fields['password']);
       
        $fields['role'] = "user";
        $user = User::create($fields);
        $token = $user->createToken($request->nom);
        return [
            'user' => $user, 
            'token' => $token->plainTextToken
        ];
    }

    public function login(LoginFormRequest $request){
        $fields = $request->validated();
        $userExists = User::where('email', $request->login)->orWhere('telephone', $request->login)->first();

        if(!$userExists || !Hash::check($request->password, $userExists->password)){
            return ['message' => 'login/mot de passe incorrect.', "data" => $fields];
        }
    
        $token = $userExists->createToken($userExists->nom);
        return [
            'user' => $userExists, 
            'token' => $token->plainTextToken,
            
        ];
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return ['message' => 'you are logged out',
        'loggedOut' => true,
        
        ];

        //return "Ok";
        // "userTokens" => $request->user(),
        //session()->flush(); // removes all session data
        // $tokenString = $request->bearerToken(); // Just the token string, no "Bearer"
        // $tokenFromRequest = PersonalAccessToken::findToken($tokenString);
    }
}