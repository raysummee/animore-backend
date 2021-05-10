<?php

use App\Http\Controllers\API\BookVeterinaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//auth
Route::post('/register', 'App\Http\Controllers\API\AuthController@register');
Route::post('/login', 'App\Http\Controllers\API\AuthController@login');
Route::post('/logout', 'App\Http\Controllers\API\AuthController@logout')->middleware("auth:api");

//pet
Route::get('/pet', [App\Http\Controllers\API\PetController::class, 'index'])->middleware("auth:api");
Route::post('/pet', [App\Http\Controllers\API\PetController::class, 'store'])->middleware("auth:api");
Route::post('/pet/{pet}', [App\Http\Controllers\API\PetController::class, 'update'])->middleware("auth:api");
Route::delete('/pet/{pet}', [App\Http\Controllers\API\PetController::class, 'delete'])->middleware("auth:api");

//important date
Route::get("/important_date/{pet}", [App\Http\Controllers\API\PetImportantDateController::class, 'index'])->middleware('auth:api');
Route::post("/important_date/{pet}", [App\Http\Controllers\API\PetImportantDateController::class, 'store'])->middleware('auth:api');
Route::delete("/important_date/{pet}/{date}", [App\Http\Controllers\API\PetImportantDateController::class, 'delete'])->middleware('auth:api');
Route::put("/important_date/{pet}/{date}", [\App\Http\Controllers\API\PetImportantDateController::class, 'update'])->middleware('auth:api');

//todos
Route::get("/todos/{pet}",[App\Http\Controllers\API\PetDailiesController::class, "index"])->middleware("auth:api");
Route::post("/todos/{pet}",[App\Http\Controllers\API\PetDailiesController::class, "store"])->middleware("auth:api");
Route::delete("/todos/{petDailies}", [App\Http\Controllers\API\PetDailiesController::class, "delete"])->middleware("auth:api");
Route::put("/todos/{petDailies}", [\App\Http\Controllers\API\PetDailiesController::class, "update"])->middleware("auth:api");

//user
Route::post('/user', [\App\Http\Controllers\API\UserController::class, 'store']);
Route::put('/user', [\App\Http\Controllers\API\UserController::class, 'update'])->middleware("auth:api");

//util
Route::post('/upload/image', [App\Http\Controllers\API\UtilityController::class, 'imageUpload'])->middleware("auth:api");
Route::get('/upload/image/{filename}',[App\Http\Controllers\API\UtilityController::class, 'imageShow'])->where('filename', '[A-Za-z0-9/.-]+');

//veterinary book
Route::post('/veterinary/book', [BookVeterinaryController::class, 'store'])->middleware("auth:api");
Route::post('/veterinary/book/status/{vetBook}', [BookVeterinaryController::class, 'statusChange'])->middleware("auth:api");
Route::get('/veterinary/book', [BookVeterinaryController::class, 'index'])->middleware("auth:api");

//veterinary
Route::post('/veterinary', [App\Http\Controllers\API\VeterinaryController::class, 'store'])->middleware("auth:api");
Route::get('/veterinary', [App\Http\Controllers\API\VeterinaryController::class, 'index'])->middleware('auth:api');
Route::get('/veterinary/auth', [\App\Http\Controllers\API\VeterinaryController::class, 'authorizeVeterinary'])->middleware('auth:api');
Route::get('/veterinary/{userId}', [\App\Http\Controllers\API\VeterinaryController::class, 'detail'])->middleware('auth:api');
Route::delete('/veterinary', [App\Http\Controllers\API\VeterinaryController::class, 'delete'])->middleware("auth:api");
Route::put('/veterinary', [App\Http\Controllers\API\VeterinaryController::class, 'update'])->middleware("auth:api");

//store/merchant
Route::get("/merchant",[App\Http\Controllers\API\MerchantController::class, 'index']);
Route::post("/merchant", [App\Http\Controllers\API\MerchantController::class, "store"])->middleware("auth:api");
Route::delete("/merchant", [App\Http\Controllers\API\MerchantController::class, "delete"])->middleware("auth:api");
Route::put("/merchant", [App\Http\Controllers\API\MerchantController::class, "update"])->middleware("auth:api");
