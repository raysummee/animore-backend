<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Veterinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VeterinaryController extends Controller
{
    public function index(Request $request)
    {
        $data =  Veterinary::all();
        return response(['veterinary'=>$data]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "",
            "available" => '',
            "contact_no" => '',
            "email" => '',
            "desc" => ''
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
            "desc" => 'required'
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
