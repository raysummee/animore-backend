<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetDailies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PetDailiesController extends Controller
{
    public function index(Request $request, Pet $pet)
    {
        if($pet->user->id == $request->user()->id) {
            $mon = $pet->dailies()->where("week", "mon")->oldest()->get();
            $tues = $pet->dailies()->where("week", "tue")->get();
            $wed = $pet->dailies()->where("week", "wed")->get();
            $thus = $pet->dailies()->where("week", "thu")->get();
            $fri = $pet->dailies()->where("week", "fri")->get();
            $sat = $pet->dailies()->where("week", "sat")->get();
            $sun = $pet->dailies()->where("week", "sun")->get();


            $arrDaily = array("mon"=>$mon, "tue"=>$tues, "wed"=>$wed, "thu"=>$thus, "fri"=>$fri, "sat"=>$sat, "sun"=>$sun);

            return response(['todos' => $arrDaily]);
        }else{
            return response(["message"=>"Forbidden"], 403);
        }
    }

    public function store(Request $request, Pet $pet)
    {
        if ($pet->user->id == $request->user()->id) {
            $validator = Validator::make($request->all(),[
                "task_name" => "required",
                "week" => "required|in:mon,tue,wed,thu,fri,sat,sun",
                "time" => "required|date_format:H:i:s"
            ]);

            try {
                $data = $validator->validate();
            } catch (ValidationException $e) {
                return response(["message"=>$e->getMessage()], 400);
            }
            $pet->dailies()->create($data);

            return response(["todos"=> $data], 201);
        }else{
            return response(["message"=>"Forbidden"], 403);
        }
    }

    public function delete(Request $request, PetDailies $petDailies)
    {
        if ($petDailies->pet->user->id == $request->user()->id) {
            try {
                $petDailies->delete();
            } catch (\Exception $e) {
                return response(["message"=>$e->getMessage()], 500);
            }
            return response(["message"=>"deleted"]);
        }else{
            return response(["message"=>"Forbidden"], 403);
        }
    }
}
