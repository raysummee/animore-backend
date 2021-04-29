<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VetBook;
use App\Models\Veterinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VeterinaryController extends Controller
{
    public function index(Request $request)
    {
        $data = Veterinary::all();
        $pet = $request->user()->pet[0];
        $vetBook = $pet->vetBooks()->latest()->get();

        $output = [];


        foreach ($data as $vet){
            if($vetBook->contains("veterinary_id","=",$vet->id)){
                $status = $vetBook->firstWhere("veterinary_id","=", $vet->id)->status;
                array_push($output, array_merge($vet->toArray(), ["status"=> $status ]));
            }else{
                array_push($output, array_merge($vet->toArray(), ["status"=>"none"]));
            }
        }

        return response(['veterinary'=>$output]);
    }

    public function authorizeVeterinary(Request $request)
    {
        $veterinary = $request->user()->veterinary;
        if($veterinary==null){
            return response(["message"=>"No veterinary found for the user"],404);
        }
        return response(["veterinary"=>$veterinary]);
    }

    public function detail(Request $request, $userId)
    {
        $user = User::find($userId);

        if($user==null){
            return response(["message"=>"User not found"],404);
        }
        $veterinary = $user->veterinary;
        if($veterinary==null){
            return response(["message"=>"No veterinary found for the user"],404);
        }
        return response(["veterinary"=>$veterinary]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "",
            "available" => '',
            "contact_no" => '',
            "email" => '',
            "desc" => '',
            "location" => '',
            "star" => 'integer'
        ]);

        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()]);
        }
        $data = $request->user()->veterinaryAdmin()->update($data);

        return response(['veterinary'=>$data==0?"could not update":"updated"]);
    }

    public function store(Request $request)
    {

        if($request->user()->veterinary_id!=null){
            return response(["message"=>"already has a veterinary"], 400);
        }

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "available" => 'required',
            "contact_no" => 'required',
            "email" => 'required',
            "desc" => 'required',
            "location" => 'required',
            "star" => 'required|integer'
        ]);

        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()]);
        }
        $data = $request->user()->veterinaryAdmin()->create($data);
        $request->user()->update([
            "veterinary_id" => $data->id
        ]);
        return response(['veterinary'=>$data]);
    }

    public function delete(Request $request)
    {
        $admin = $request->user()->veterinaryAdmin;
        User::where('veterinary_id',$admin->id)->update(['veterinary_id'=>null]);
        $request->user()->veterinaryAdmin()->delete();
        return response(['message'=>"deleted"]);
    }
}
