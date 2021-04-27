<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Inline\Element\Image;

class UtilityController extends Controller
{
    public function imageUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,jpg,png,gif'
        ]);

        if($validator->fails()){
            return response(["message"=> $validator->errors()->getMessages()]);
        }

        $extension = $request->file("file")->extension();

        $userWithoutSpace = str_replace(" ","_", $request->user()->name);

        $file = $request->file("file")->storeAs(
            'public/'.$request->user()->id,
            $userWithoutSpace."-".Str::random(20).".".$extension
        );
        $file = explode("public/", $file);

        return response(["message"=>"upload/image/".$file[1]]);
    }

    public function imageShow(Request $request, $filename)
    {

        $imageUserId = explode("/", $filename, 2);

        if(true||$request->user()->id == $imageUserId[0]) {

            $path = storage_path('app/public/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }else{
            return response(['message'=>'unauthorised']);
        }
    }
}
