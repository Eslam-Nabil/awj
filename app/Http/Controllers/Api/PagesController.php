<?php

namespace App\Http\Controllers\Api;

use App\Models\Pages;
use App\Models\HomePage;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return view('dashboard');
    }
    public function home(){
        $pagedata=Pages::where('slug','home')->first();
        return view('pages.dashboard-home',compact('pagedata'));
    }

    public function home_update( Request $request ){
       $page = (Pages::where('slug','home')->first() ? : new Pages);
        $page->page_name=$request->page_name;
        $page->slug=$request->slug;
        $page->slogan=$request->slogan;
        $page->main_title=$request->main_title;
        $page->meta_title=$request->meta_title;
        $page->meta_description=$request->meta_description;
        $page->main_description=$request->main_description;
        $page->second_description=$request->second_description;

        if($request->hasFile('main_image_path')){
            $request->main_image_path->store('images', 'public');
            $page->main_image_path=$request->main_image_path->hashName();
        }
        if($request->hasFile('second_image_path')){
            $request->second_image_path->store('images', 'public');
            $page->second_image_path=$request->second_image_path->hashName();
        }
        $page->save();
        return redirect()->route('home.index');
    }

    public function about(){
        $pagedata=Pages::where('slug','about-us')->first();
        return view('pages\dashboard-about',compact('pagedata'));
    }

    public function about_update( Request $request ){
        $page = (Pages::where('slug','about-us')->first() ? : new Pages);
        $page->page_name=$request->page_name;
        $page->slug=$request->slug;
        $page->slogan=$request->slogan;
        $page->main_title=$request->main_title;
        $page->meta_title=$request->meta_title;
        $page->meta_description=$request->meta_description;
        $page->main_description=$request->main_description;
        $page->second_description=$request->second_description;

        if($request->hasFile('main_image_path')){
            $request->main_image_path->store('images', 'public');
            $page->main_image_path=$request->main_image_path->hashName();
        }
        if($request->hasFile('second_image_path')){
            $request->second_image_path->store('images', 'public');
            $page->second_image_path=$request->second_image_path->hashName();
        }
        $page->save();
        return redirect()->route('about.index');
    }

    public function contact(){
        $pagedata=Pages::where('slug','contact-us')->first();
        return view('pages\dashboard-contact',compact('pagedata'));
    }

    public function contact_update( Request $request ){

        return redirect()->route('contact.index');
    }
    public function categories(){
        return view('pages\dashboard-categories');
    }

    public function categories_update( Request $request ){

        return redirect()->route('catgories.index');
    }


}
