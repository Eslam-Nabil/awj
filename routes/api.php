<?php
use App\Models\SectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\SectionTypeController;
use App\Http\Controllers\Api\AdditionalSectionController;

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

Route::get('/test',[UserController::class,'test']);
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

Route::group(['prefix'=>'admin'],function () {

    Route::get('/user/delete/{id}',[UserController::class,'destroy']);
    Route::get('/users',[UserController::class,'index']);

    Route::post('/section/add',[AdditionalSectionController::class,'store']);
    Route::get('/section/delete/{id}',[AdditionalSectionController::class,'destroy']);
    Route::post('/section/update/{id}',[AdditionalSectionController::class,'update']);

    Route::get('/team/members',[TeamController::class,'index']);
    Route::post('/team/add',[TeamController::class,'store']);
    Route::get('/team/delete/{id}',[TeamController::class,'destroy']);
    Route::post('/team/update/{id}',[TeamController::class,'update']);

    Route::get('/home',[PagesController::class,'home']);
    Route::post('/home/update',[PagesController::class,'home_update']);

    Route::get('/about',[PagesController::class,'about']);
    Route::post('/about/update',[PagesController::class,'about_update']);
    Route::post('/team/update',[PagesController::class,'about_update']);

    Route::get('/types',[SectionTypeController::class,'index']);
    Route::post('/types/add',[SectionTypeController::class,'store']);
    Route::get('/sections',[AdditionalSectionController::class,'index']);

});
