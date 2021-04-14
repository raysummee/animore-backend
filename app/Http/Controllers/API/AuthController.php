<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\at;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validateData = Validator::make($request->all(), [
            'name' => ['required','max:55'],
            'email' => ['email','required','unique:users'],
            'password' => ['confirmed','required']
        ]);

        if($validateData->fails()){
            return response(['message'=>$validateData->messages(),], 400);
        }

        try {
            $validateData = $validateData->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 400);
        }


        $validateData['password'] = bcrypt($request->password);

        $user = User::create($validateData);

        $profile = $user->profile()->save(new Profile([
            "role" => "basic"
        ]));

        $accessToken = $user->createToken('authToken')->accessToken;

        $output = (array_merge($user->only("id","name", "email"), $profile->only("role", "phone", "image", "dob")));

        return response(['user'=>$output, 'access_token'=>$accessToken]);
    }

    public function login(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'email' => ['email','required'],
            'password' => ['required']
        ]);

        if($validateData->fails()){
            return response(['message'=>$validateData->messages()], 401);
        }

        try {
            $loginData = $validateData->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 401);
        }

        if(!auth()->attempt($loginData)){
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $user = auth()->user();
        $profile = $user->profile;

        $output = (array_merge($user->only("id", "name", "email"), $profile->only("role", "phone", "image", "dob")));

        return response(['user'=>$output, 'access_token'=>$accessToken]);
    }

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->revoke();
        return response(["message"=>"You have been log out"], 200);
    }
}
