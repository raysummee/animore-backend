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
            $mon = $pet->dailies()->where("week", "monday")->oldest()->get();
            $tues = $pet->dailies()->where("week", "tuesday")->get();
            $wed = $pet->dailies()->where("week", "wednesday")->get();
            $thus = $pet->dailies()->where("week", "thursday")->get();
            $fri = $pet->dailies()->where("week", "friday")->get();
            $sat = $pet->dailies()->where("week", "saturday")->get();
            $sun = $pet->dailies()->where("week", "sunday")->get();


            $arrDaily = array("mon"=>$mon, "tues"=>$tues, "wed"=>$wed, "thus"=>$thus, "fri"=>$fri, "sat"=>$sat, "sun"=>$sun);

            return response(['todos' => $arrDaily]);
        }else{
            return response(["message"=>"unauthorised"]);
        }
    }

    public function store(Request $request, Pet $pet)
    {
        if ($pet->user->id == $request->user()->id) {
            $validator = Validator::make($request->all(),[
                "task_name" => "required",
                "week" => "required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday",
                "time" => "required|date_format:h:i:s"
            ]);

            try {
                $data = $validator->validate();
            } catch (ValidationException $e) {
                return response(["message"=>$e->getMessage()]);
            }
            $pet->dailies()->create($data);

            return response(["todos"=> $data]);
        }else{
            return response(["message"=>"unauthorised"]);
        }
    }

    public function delete(Request $request, PetDailies $petDailies)
    {
        if ($petDailies->pet->user->id == $request->user()->id) {
            try {
                $petDailies->delete();
            } catch (\Exception $e) {
                return response(["message"=>$e->getMessage()]);
            }
            return response(["message"=>"deleted"]);
        }else{
            return response(["message"=>"unauthorised"]);
        }
    }
}