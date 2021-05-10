<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MerchantController extends Controller
{
    public function index()
    {
       $data = Store::all();
       return response(['merchants'=>$data]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "store_name" => "",
            "availability" => 'boolean',
            "contact_no" => 'numeric',
            "email" => 'email',
            "desc" => '',
            "address" => ''
        ]);

        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 400);
        }
        $status = $request->user()->storeAdmin()->update($data);

        if($status==0){
            return response(['message'=>"Could not update"], 500);
        }else{
            return response(["message"=>"Updated"], 200);
        }
    }

    public function store(Request $request)
    {

        if($request->user()->store_id!=null){
            return response(["message"=>"already has a store"], 400);
        }

        $validator = Validator::make($request->all(), [
            "store_name" => "required",
            "availability" => 'required|boolean',
            "contact_no" => 'required|numeric',
            "email" => 'required|email',
            "desc" => 'required',
            "address" => 'required'
        ]);

        try {
            $data = $validator->validate();
        } catch (ValidationException $e) {
            return response(['message'=>$e->getMessage()], 400);
        }
        $data = $request->user()->storeAdmin()->create($data);
        $request->user()->update([
            "store_id" => $data->id
        ]);
        return response(['store'=>$data], 201);
    }

    public function delete(Request $request)
    {
        $admin = $request->user()->storeAdmin;
        if($admin==null){
            return response(["message"=>"User doesn't have a store"], 404);
        }
        $request->user()->storeAdmin()->delete();
        return response(['message'=>"deleted"]);
    }
}
