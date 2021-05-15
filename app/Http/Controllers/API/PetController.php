<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use http\Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PetController extends Controller
{
    public function index(Request $request)
    {

        $pet = $request->user()->pet()->with(
            "importantDate",
            "dailies",
        )->get();
        return response(['pet'=>$pet]);
    }

    public function store(Request $request)
    {
        try {
            $validateData = Validator::make(request()->all(),[
                "name" => "required",
                "bread" => "required",
                "dob" => 'required|date',
                "image" => "url",
                "type" => "required"
            ]);
            $validateData = $validateData->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage(), 400]);
        }

        $data = $request->user()->pet()->create($validateData);

        return response(["pet" => $data], 201);
    }

    public function update(Request $request, Pet $pet)
    {
        if($pet->user->id == $request->user()->id) {
            $data = $request->validate([
                "name" => "",
                "bread" => "",
                "dob" => "date",
                "image" => "url",
                "type" => ""
            ]);
            $updated=$pet->update($data);
            if($updated==0){
                return response(['message'=>"Could not update"], 500);
            }else{
                return response(['message'=>"Updated"]);
            }
        }else{
            return response(['message'=> 'Forbidden'], 403);
        }
    }

    public function delete(Request $request, Pet $pet)
    {
        if ($pet->user->id == $request->user()->id) {
            try {
                $pet->delete();
                return response(['message'=>"deleted"]);
            } catch (\Exception $e) {
                return response(["message"=>$e->getMessage()], 500);
            }
        }else{
            return response(['message'=> 'Forbidden'], 403);
        }
    }
}
