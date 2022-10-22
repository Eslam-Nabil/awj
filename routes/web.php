<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PagesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::group(['middleware'=>'auth'],function () {

//      Route::get('/dashboard',[PagesController::class,'index'])->name('dashboard');

//     Route::get('/home',[PagesController::class,'home'])->name('home.index');
//     Route::post('/home/update',[PagesController::class,'home_update'])->name('home.update');

//     Route::get('/about',[PagesController::class,'about'])->name('about.index');
//     Route::post('/about/update',[PagesController::class,'about_update'])->name('about.update');

//     Route::get('/contact',[PagesController::class,'contactus'])->name('contact.index');
//     Route::post('/contact/update',[PagesController::class,'contact_update'])->name('contact.update');

//     Route::get('/categories',[PagesController::class,'categories'])->name('categories.index');
//     Route::post('/categories/update',[PagesController::class,'categories_update'])->name('categories.update');

//     Route::get('/team',[TeamController::class,'index'])->name('team.index');
//     Route::post('/contact/update',[PagesController::class,'contact_update'])->name('contact.update');
// });
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

