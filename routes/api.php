<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PagesController;

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

// Route::group(['prefix'=>'page','namespace'=>'Api'],function () {
//         route::get('/home',[]);
// });

Route::post('/test',[UserController::class,'test']);
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

Route::group(['prefix'=>'admin'],function () {
    Route::get('/users',[UserController::class,'index']);
    Route::get('/home',[PagesController::class,'home']);
    Route::post('/home/update',[PagesController::class,'home_update']);
    Route::get('/about',[PagesController::class,'about']);
    Route::post('/about/update',[PagesController::class,'about_update']);
});
