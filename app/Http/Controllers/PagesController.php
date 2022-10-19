<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return view('dashboard');
    }
    public function home(){
        $homedata=HomePage::first();
        return view('pages\dashboard-home',compact('homedata'));
    }

    public function home_update( Request $request ){
        $home =HomePage::first() ?? $home= new HomePage ;
        $home->page_name=$request->page_name;
        if($request->hasFile('first_side_image')){
            $request->first_side_image->store('images', 'public');
            $home->first_side_image=$request->first_side_image->hashName();
        }
        if($request->hasFile('third_side_image')){
            $request->third_side_image->store('images', 'public');
            $home->third_side_image=$request->third_side_image->hashName();
        }
        $home->first_section_title=$request->first_section_title;
        $home->third_section_title=$request->third_section_title;
        $home->third_left_description=$request->third_left_description;
        $home->third_right_description=$request->third_right_description;
        $home->save();
        return redirect()->route('home.index');
    }
    public function about(){

        return view('pages\dashboard-about');
    }

    public function about_update( Request $request ){

        return redirect()->route('about.index');
    }
    public function contatcus(){

        return view('pages\dashboard-contact');
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
