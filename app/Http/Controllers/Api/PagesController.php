<?php

namespace App\Http\Controllers\Api;

use App\Models\Pages;
use App\Models\HomePage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function index(){
        return view('dashboard');
    }
    public function home(){
        $pagedata=Pages::where('slug','home')->first();
        return response()->json(['success' => true,'data'=>$pagedata], 200);
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
            $request->main_image_path->move(public_path('images'), $request->main_image_path->getClientOriginalName());
            $page->main_image_path=asset('images/'.$request->main_image_path->getClientOriginalName());
        }
        if($request->hasFile('second_image_path')){
            $request->second_image_path->move(public_path('images'), $request->second_image_path->getClientOriginalName().time());
            $page->second_image_path=asset('images/'.$request->second_image_path->getClientOriginalName());
        }
        $page->save();
        return response()->json(['success' => true,'data'=>$page], 200);
    }

    public function about(){
        $pagedata=Pages::where('slug','about-us')->first();
        return response()->json(['success' => true,'data'=>$pagedata], 200);
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
            // $request->main_image_path->store('images', 'public');
            $request->main_image_path->move(public_path('images'), $request->main_image_path->getClientOriginalName());
            $page->main_image_path=asset('images/'.$request->main_image_path->getClientOriginalName());
          //  dd($request);

        }

        if($request->hasFile('second_image_path')){
            $request->second_image_path->move(public_path('images'), $request->second_image_path->getClientOriginalName().time());
            $page->second_image_path=asset('images/'.$request->second_image_path->getClientOriginalName());
        }
        $page->save();
        return response()->json(['success' => true,'data'=>$page], 200);
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
