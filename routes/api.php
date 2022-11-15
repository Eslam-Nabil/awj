<?php
use App\Models\SectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LanguageController;
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

Route::get('/test/{id}',[UserController::class,'test']);
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

Route::group(['prefix'=>'admin'],function () {
    Route::get('/users',[UserController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::get('/user/delete/{id}',[UserController::class,'destroy'])->middleware(['auth:api','role:admin']);

    Route::post('/section/add',[AdditionalSectionController::class,'store'])->middleware(['auth:api']);
    Route::get('/section/delete/{id}',[AdditionalSectionController::class,'destroy'])->middleware(['auth:api','role:admin']);
    Route::post('/section/update/{id}',[AdditionalSectionController::class,'update'])->middleware(['auth:api','role:admin']);

    Route::get('/team/members',[TeamController::class,'index']);
    Route::post('/team/add',[TeamController::class,'store'])->middleware(['auth:api','role:admin']);
    Route::get('/team/delete/{id}',[TeamController::class,'destroy'])->middleware(['auth:api','role:admin']);
    Route::post('/team/update/{id}',[TeamController::class,'update'])->middleware(['auth:api','role:admin']);

    Route::get('/home',[PagesController::class,'home']);
    Route::post('/home/update',[PagesController::class,'home_update'])->middleware(['auth:api','role:admin']);

    Route::get('/about',[PagesController::class,'about']);
    Route::post('/about/update',[PagesController::class,'about_update'])->middleware(['auth:api','role:admin']);

    Route::get('/types',[SectionTypeController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::post('/types/add',[SectionTypeController::class,'store'])->middleware(['auth:api','role:admin']);
    Route::get('/sections',[AdditionalSectionController::class,'index'])->middleware(['auth:api']);
    Route::get('/languages',[LanguageController::class,'index']);
});

Route::group(['prefix'=>'article'],function () {
    Route::get('/{lang}',[ArticleController::class,'index']);
    Route::get('',[ArticleController::class,'getArticlesByUser'])->middleware(['auth:api']);
    Route::post('/add',[ArticleController::class,'store'])->middleware(['auth:api','role:admin|student|author']);
});

Route::group(['prefix'=>'comment'],function () {
    Route::get('/',[CommentController::class,'index']);
    Route::post('/add',[CommentController::class,'store'])->middleware('auth:api');
});
