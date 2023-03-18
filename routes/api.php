<?php
use App\Models\SectionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\ContactusController;
use App\Http\Controllers\Api\NewsLetterController;
use App\Http\Controllers\Api\SectionTypeController;

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
Route::get('/user/{id}',[UserController::class,'show'])->middleware(['auth:api','role:reviewer|admin']);
Route::get('/author/{id}',[UserController::class,'showAuthor']);
Route::post('/register',[UserController::class,'register']);
Route::post('user/changepassword', [UserController::class, 'changePassword'])->middleware(['auth:api'])->name('changePassword');
Route::post('/user/update',[UserController::class,'update'])->middleware(['auth:api']);
Route::post('/user/picture',[UserController::class,'updateProfilePicture'])->middleware(['auth:api']);
Route::post('/login',[UserController::class,'login']);
Route::post('/{lang}/search',[PagesController::class,'search']);

Route::group(['prefix'=>'admin'],function () {
    Route::get('/users',[UserController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::get('/user/delete/{id}',[UserController::class,'destroy'])->middleware(['auth:api','role:admin']);

    Route::post('/section/add',[SectionController::class,'store'])->middleware(['auth:api']);
    Route::get('/section/delete/{id}',[SectionController::class,'destroy'])->middleware(['auth:api','role:admin']);
    Route::get('/sections',[SectionController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::post('/section/update/{id}',[SectionController::class,'update'])->middleware(['auth:api','role:admin']);

    Route::get('//team/members',[TeamController::class,'index']);
    Route::post('/team/add',[TeamController::class,'store'])->middleware(['auth:api','role:admin']);
    Route::get('/team/delete/{id}',[TeamController::class,'destroy'])->middleware(['auth:api','role:admin']);
    Route::post('/team/update/{id}',[TeamController::class,'update'])->middleware(['auth:api','role:admin']);

    Route::get('//home',[PagesController::class,'home']);
    Route::post('/home/update',[PagesController::class,'home_update'])->middleware(['auth:api','role:admin']);

    Route::get('//about',[PagesController::class,'about']);
    Route::post('/about/update',[PagesController::class,'about_update'])->middleware(['auth:api','role:admin']);

    Route::get('/categories',[PagesController::class,'categories']);
    Route::post('/categories/update',[PagesController::class,'categories_update'])->middleware(['auth:api','role:admin']);

    Route::get('/contact',[ContactusController::class,'index']);
    Route::post('/contact/update',[ContactusController::class,'update'])->middleware(['auth:api','role:admin']);;

    Route::get('/types',[SectionTypeController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::post('/types/add',[SectionTypeController::class,'store'])->middleware(['auth:api','role:admin']);
    Route::get('/{lang}/sections',[SectionController::class,'index'])->middleware(['auth:api']);
    Route::get('/languages',[LanguageController::class,'index']);
    ## use only with  developer
    Route::post('/pages/add',[PagesController::class,'add_page'])->middleware(['auth:api','role:admin']);
});

Route::group(['prefix'=>'front'],function () {
    Route::get('{lang}/sections',[SectionController::class,'front_index']);
    Route::get('/{lang}/team/members',[TeamController::class,'front_index']);
    Route::get('/{lang}/home',[PagesController::class,'front_home']);
    Route::get('/{lang}/about',[PagesController::class,'front_about']);
    Route::get('/{lang}/categories',[PagesController::class,'front_categories']);
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

Route::group(['prefix'=>'paypal'],function () {
    Route::get('/capture_payment_article',[ArticleController::class,'capture_payment_article'])->name('paypal_capture');
    Route::get('/capture_payment_register',[ArticleController::class,'capture_payment_register'])->name('paypal_register_capture');
    Route::get('/cancel_payment_article',[ArticleController::class,'cancel_payment_article'])->name('paypal_cancel');
});

Route::group(['prefix'=>'newsletter'],function () {
    Route::get('/all',[NewsLetterController::class,'index'])->middleware(['auth:api','role:admin']);
    Route::post('/register',[NewsLetterController::class,'store']);
    Route::post('/delete',[NewsLetterController::class,'destroy'])->middleware(['auth:api','role:admin']);
});

Route::group(['prefix'=>'cart'],function () {
    Route::post('/add',[CartController::class,'addToCart'])->middleware(['auth:api','role:admin|student|author']);
    Route::get('/',[CartController::class,'getUserCart'])->middleware(['auth:api','role:admin|student|author']);
    Route::delete('/delete-item',[CartController::class,'deleteFromCart'])->middleware(['auth:api','role:admin|student|author']);
    Route::delete('/delete',[CartController::class,'deleteAllCart'])->middleware(['auth:api','role:admin|student|author']);
  //  Route::post('/delete',[CartController::class,'deleteFromCart'])->middleware(['auth:api','role:admin|student|author']);
});

Route::group(['prefix'=>'article'],function () {
    Route::get('/{lang}',[ArticleController::class,'index']);
    Route::get('approved/{lang}',[ArticleController::class,'approved_articles']);
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
    Route::post('/certificate',[ArticleController::class,'attach_certificate'])->middleware(['auth:api','role:reviewer|admin']);
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

Route::group(['prefix'=>'setting'],function () {
    Route::get('/',[SettingController::class,'index']);
    Route::post('/edit',[SettingController::class,'edit'])->middleware(['auth:api','role:admin']);
});
