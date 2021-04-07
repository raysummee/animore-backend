<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetImportantEvent;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PetImportantDateController extends Controller
{
    public function index(Request $request, Pet $pet)
    {
        if ($pet->user->id == $request->user()->id) {
            $importantDate = $pet->importantDate;
            return response(['important_dates'=>$importantDate]);
        }else{
            return response(['message'=>'unauthorised'], 500);
        }
    }

    public function store(Request $request, Pet $pet)
    {
        if($pet->user->id == $request->user()->id){
            $validator = Validator::make($request->all(),[
                "name" => "required",
                "date_time" => 'required|date_format:Y-m-d H:i:s'
            ]);
            try {
                $validatedData = $validator->validate();
            } catch (ValidationException $e) {
                return response(['message'=> $e->getMessage()]);
            }

            $pet->importantDate()->create($validatedData);

            return response(['message'=> 'uploaded']);
        }else{
            return response(['message'=>'unauthorised'], 500);
        }
    }

    public function update(Request $request, Pet $pet, PetImportantEvent $date)
    {
        if($pet->user->id == $request->user()->id){
            $validator = Validator::make($request->all(),[
                "name" => "",
                "date_time" => 'date_format:Y-m-d H:i:s'
            ]);
            try {
                $data = $validator->validate();
            } catch (ValidationException $e) {
                return response(['message'=> $e->getMessage()]);
            }
            $updated = $date->update($data);
            if($updated == true){
                return response(['message'=>'updated'], 200);
            }else{
                return response(['message'=>'error updating'], 400);
            }
        }
    }

    public function delete(Request $request, Pet $pet, PetImportantEvent $date)
    {
        if($pet->user->id == $request->user()->id){
            try {
                $date->delete();
            } catch (\Exception $e) {
                return response(['message'=>$e->getMessage()]);
            }
            return response(['message'=> 'deleted']);
        }else{
            return response(['message'=>'unauthorised'], 500);
        }
    }
}
