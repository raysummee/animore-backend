<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->profile;

        return response([ "profile"=>$profile]);
    }

    public function update(Request $request)
    {
        $validateData = $request->validate([
            "role" => "",
            "phone" => "numeric",
            "dob" => "date",
            "image" => ""
        ]);

        $request->user()->profile()->update($validateData);

        return response(['message'=>"updated"]);
    }


}
