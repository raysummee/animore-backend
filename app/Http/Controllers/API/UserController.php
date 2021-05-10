<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function store(Request  $request)
    {
        $validateData = Validator::make($request->all(), [
            'name' => ['required','max:55'],
            'email' => ['email','required','unique:users'],
            'password' => ['confirmed','required'],
            'role' => ['in:basic,pro,admin,doctor,merchant'],
            'dob' => ['datetime'],
            'phone' => ['integer'],
            'image' => ['']
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
        $validateData['role'] = "basic";

        $user = User::create($validateData);

        return response(['user'=>$user], 201);

    }

    public function update(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'name' => ['max:55'],
            'email' => ['email','unique:users'],
            'role' => ['in:basic,pro,admin,doctor,merchant'],
            'dob' => ['datetime'],
            'phone' => ['integer'],
            'image' => ['']
        ]);

        if($validateData->fails()){
            return response(['message'=>$validateData->messages(),], 400);
        }

        try {
            $validateData = $validateData->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 400);
        }

        $user = $request->user();

        $user->update($validateData);
        $user->fresh();
        return response(["message"=>"updated", "user"=>$user]);
    }
}
