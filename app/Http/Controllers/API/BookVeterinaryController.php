<?php

namespace App\Http\Controllers\API;

use App\Events\VetBook\onCreateVetBook;
use App\Events\VetBook\onStatusChangeVetBook;
use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use App\Models\VetBook;
use App\Models\Veterinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BookVeterinaryController extends Controller
{

    public function index(Request $request)
    {
        $vetId = $request->user()->veterinary->id;

        $bookings = VetBook::where("veterinary_id","=",$vetId)
            ->where("status","=", "booked")
            ->orWhere("status","=", "accepted")
            ->with("pet.user")
            ->get();

        return response()->json(["vetBook"=>$bookings]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "pet_id" => "required|integer",
            "veterinary_id" => "required|integer",
            "onDate" => "required|date_format:Y-m-d H:i:s",
            "subject" => "required"
        ]);

        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 400);
        }

        $pet = Pet::find($data["pet_id"]);

        if($pet==null){
            return response(["message"=>"pet of id ".$data["pet_id"]." doesn't exist"], 404);
        }

        $vet = Veterinary::find($data["veterinary_id"]);

        if($vet==null){
            return response(["message"=>"veterinary of id ".$data["veterinary_id"]." doesn't exist"], 404);
        }


        if($request->user()->id == $pet->user->id){
            $data["status"] = "booked";
            $status = $pet->vetBooks()->create($data);
            $status->pet->user;
            event(new onCreateVetBook($status));
            return response()->json(["book"=>$status], 201);
        }else{
            return response()->json(["message"=>"Forbidden"], 403);
        }
    }

    public function statusChange(Request $request, VetBook $vetBook)
    {
        $validator = Validator::make($request->all(), [
            "status" => "required|in:booked,cancel,denied,accepted,completed"
        ]);

        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 400);
        }


        if($vetBook->veterinary==null){
            return response(["message"=>"veterinary doesn't exist or deleted"], 404);
        }


        foreach ($vetBook->veterinary->users as $user){
            if($request->user()->id == $user->id){
                $result = $vetBook->update($data);
                if($result) {
                    $vetBook->fresh();
                    event(new onStatusChangeVetBook($vetBook));
                    return response(["message" => $result, "book"=>$vetBook]);
                }else{
                    return response(["message"=>"error"], 500);
                }
            }
        }

        return response(['message'=>"Forbidden"], 403);


    }
}
