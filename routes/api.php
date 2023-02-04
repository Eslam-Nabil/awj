<?php
use App\Models\SectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ContactusController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\SectionTypeController;
use App\Http\Controllers\Api\TaskController;

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
Route::post('/user/update',[UserController::class,'update'])->middleware(['auth:api']);
Route::post('/login',[UserController::class,'login']);
Route::post('/{lang}/search',[PagesController::class,'search']);

Route::group(['prefix'=>'admin'],function () {
    Route::get('/users',[UserController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::get('/user/delete/{id}',[UserController::class,'destroy'])->middleware(['auth:api','role:admin']);

    Route::post('/section/add',[SectionController::class,'store'])->middleware(['auth:api']);
    Route::get('/section/delete/{id}',[SectionController::class,'destroy'])->middleware(['auth:api','role:admin']);
    Route::get('/sections',[SectionController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::post('/section/update/{id}',[SectionController::class,'update'])->middleware(['auth:api','role:admin']);

    Route::get('/team/members',[TeamController::class,'index']);
    Route::post('/team/add',[TeamController::class,'store'])->middleware(['auth:api','role:admin']);
    Route::get('/team/delete/{id}',[TeamController::class,'destroy'])->middleware(['auth:api','role:admin']);
    Route::post('/team/update/{id}',[TeamController::class,'update'])->middleware(['auth:api','role:admin']);

    Route::get('/home',[PagesController::class,'home']);
    Route::post('/home/update',[PagesController::class,'home_update'])->middleware(['auth:api','role:admin']);

    Route::get('/about',[PagesController::class,'about']);
    Route::post('/about/update',[PagesController::class,'about_update'])->middleware(['auth:api','role:admin']);

    Route::get('/categories',[PagesController::class,'categories']);
    Route::post('/categories/update',[PagesController::class,'categories_update'])->middleware(['auth:api','role:admin']);

    Route::get('/contact',[ContactusController::class,'index']);
    Route::post('/contact/update',[ContactusController::class,'update'])->middleware(['auth:api','role:admin']);;

    Route::get('/types',[SectionTypeController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::post('/types/add',[SectionTypeController::class,'store'])->middleware(['auth:api','role:admin']);
    Route::get('/sections',[SectionController::class,'index'])->middleware(['auth:api']);
    Route::get('/languages',[LanguageController::class,'index']);
    ## use only with  developer
    Route::post('/pages/add',[PagesController::class,'add_page'])->middleware(['auth:api','role:admin']);
});

Route::group(['prefix'=>'task'],function () {
    Route::post('/submit',[TaskController::class,'submitTask'])->middleware(['auth:api','role:student|admin']);
    Route::get('/{lang}/user',[TaskController::class,'UserTasks'])->middleware(['auth:api']);
    Route::get('/toreview',[TaskController::class,'tasksToReview'])->middleware(['auth:api','role:reviewer|admin']);
    Route::post('/approve',[TaskController::class,'approve'])->middleware(['auth:api','role:reviewer|admin']);
    Route::post('/reject',[TaskController::class,'reject'])->middleware(['auth:api','role:reviewer|admin']);
    // Route::get('/{lang}/user/',[ArticleController::class,'getArticlesByUser'])->middleware(['auth:api']);
    // Route::post('/add',[ArticleController::class,'store'])->middleware(['auth:api','role:admin|student|author']);
});

Route::group(['prefix'=>'article'],function () {
    Route::get('/{lang}',[ArticleController::class,'index']);
    Route::get('/{lang}/user/',[ArticleController::class,'getArticlesByUser'])->middleware(['auth:api']);
    Route::get('/{lang}/user/bought/',[ArticleController::class,'getUserBoughtArticles'])->middleware(['auth:api']);
    Route::post('/add',[ArticleController::class,'store'])->middleware(['auth:api','role:admin|student|author']);
    Route::get('/details/{lang}/{article}',[ArticleController::class,'show']);
    Route::post('/buy',[ArticleController::class,'buyArticle'])->middleware(['auth:api','role:admin|student']);
    Route::get('/{lang}/tasks/{articleid}',[ArticleController::class,'articleTasks'])->middleware(['auth:api','role:student|admin']);
    Route::get('/en/topublish',[ArticleController::class,'articlesToPublish'])->middleware(['auth:api','role:publisher|admin']);
    Route::post('/approve',[ArticleController::class,'approve'])->middleware(['auth:api','role:publisher|admin']);
    Route::post('/delete',[ArticleController::class,'destroy'])->middleware(['auth:api','role:publisher|admin']);
    Route::post('/{lang}/search',[ArticleController::class,'search']);
});

Route::group(['prefix'=>'comment'],function () {
    Route::get('/',[CommentController::class,'index']);
    Route::post('/add',[CommentController::class,'store'])->middleware('auth:api');
    Route::get('/approve/{id}',[CommentController::class,'approveToShow'])->middleware(['auth:api','role:admin']);
    Route::get('/delete/{id}',[CommentController::class,'destroy'])->middleware(['auth:api','role:admin']);
});
Route::group(['prefix'=>'socialmedia'],function () {
    Route::get('/',[SocialMediaController::class,'index']);
    Route::post('/add',[SocialMediaController::class,'store'])->middleware('auth:api');
    Route::get('/approve/{id}',[SocialMediaController::class,'approveToShow'])->middleware(['auth:api','role:admin']);
    Route::get('/delete/{id}',[SocialMediaController::class,'destroy'])->middleware(['auth:api','role:admin']);
});

