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
            return response(['message'=>$e->getMessage()]);
        }
        $data = $request->user()->storeAdmin()->update($data);

        return response(['merchant'=>$data==0?"could not update":"updated"]);
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
            return response(['message'=>$e->getMessage()]);
        }
        $data = $request->user()->storeAdmin()->create($data);
        $request->user()->update([
            "store_id" => $data->id
        ]);
        return response(['store'=>$data]);
    }

    public function delete(Request $request)
    {
        $admin = $request->user()->storeAdmin;
        User::where('store_id',$admin->id)->update(['store_id'=>null]);
        $request->user()->storeAdmin()->delete();
        return response(['message'=>"deleted"]);
    }
}
