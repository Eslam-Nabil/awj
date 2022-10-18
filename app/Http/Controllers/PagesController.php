<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    // public function home(){
    //         $data=HomePage::firstOrFail();
    //     return view('pages\dashboard-home',compact($data));
    // }
    public function home(){
        $homedata=HomePage::first();
        return view('pages\dashboard-home',compact('homedata'));
    }

    public function home_update( Request $request ){
        
        $home = new HomePage();
        $request->first_side_image->store('images', 'public');
        $home->first_side_image=$request->first_side_image->hashName();
        $request->third_side_image->store('images', 'public');
        $home->third_side_image=$request->third_side_image->hashName();
        $home->page_name=$request->page_name;
        $home->first_section_title=$request->first_section_title;
        $home->third_section_title=$request->third_section_title;
        $home->third_left_description=$request->third_left_description;
        $home->third_right_description=$request->third_right_description;
        $home->save();
        return redirect()->route('home.index');
    }

}
