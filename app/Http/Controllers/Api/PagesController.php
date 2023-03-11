<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Team;
use App\Models\User;
use App\Models\Pages;
use App\Models\Article;
use App\Models\HomePage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ArticleResource;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Support\Facades\Config;

class PagesController extends Controller
{

    public function home($lang){
        Config::set('translatable.locale', $lang);
        $page_data=new PageResource(Pages::where('id',1)->with('sections')->first());
        return response()->json(['success' => true,'data'=>$page_data], 200);
    }

    public function home_update(Request $request ){
        // dd($request->all());
        $data=$request->all();
        $page = (Pages::where('id',1)->first() ? Pages::where('id',1)->first() : new Pages);

        if($request->hasFile('main_image_path')){
            $request->main_image_path->move(public_path('images'), $request->main_image_path->getClientOriginalName());
            $main_image_path='images/'.$request->main_image_path->getClientOriginalName();
        }
        $data['main_image_path']= (!empty($main_image_path) ?  $main_image_path : null );

        if($request->hasFile('second_image_path')){
            $request->second_image_path->move(public_path('images'), $request->second_image_path->getClientOriginalName().time());
            $second_image_path='images/'.$request->second_image_path->getClientOriginalName();
        }
        $data['second_image_path']= (!empty($second_image_path) ?  $second_image_path : null );
            // dd($data);
        try{
            $page->update($data);
            // $page->sections()->update($data);
        }catch(Exception $e){
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
        return response()->json(['success' => true,'data'=>new PageResource($page)], 200);
    }

    public function about($lang){

        Config::set('translatable.locale', $lang);
        $page_data['about_page']=new PageResource(Pages::where('id',2)->with('sections')->first());
        $page_data['team']=TeamResource::collection(Team::all());
        return response()->json(['success' => true,'data'=>$page_data], 200);
    }

    public function about_update( Request $request ){
            $rules = RuleFactory::make([
                '%page_name%' => 'required|string',
            ]);

            // $validatedData = $request->validate($rules);

            $data=$request->all();
            $page = (Pages::where('id',2)->first() ? Pages::where('id',2)->first() : new Pages);

            if($request->hasFile('main_image_path')){
                $request->main_image_path->move(public_path('images'), $request->main_image_path->getClientOriginalName());
                $main_image_path='images/'.$request->main_image_path->getClientOriginalName();
            }
            $data['main_image_path']= (!empty($main_image_path) ?  $main_image_path : null );

            if($request->hasFile('second_image_path')){
                $request->second_image_path->move(public_path('images'), $request->second_image_path->getClientOriginalName().time());
                $second_image_path='images/'.$request->second_image_path->getClientOriginalName();
            }
            $data['second_image_path']= (!empty($second_image_path) ?  $second_image_path : null );
            try{
                $page->update($data);
                }catch(Exception $e){
                    return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
                }
            return response()->json(['success' => true,'data'=>new PageResource($page)], 200);
    }
    public function categories($lang){
        Config::set('translatable.locale', $lang);
        $page_data['categories_page']=new PageResource(Pages::where('id',3)->with('sections')->first());
        // $page_data['team']=TeamResource::collection(Team::all());
        return response()->json(['success' => true,'data'=>$page_data], 200);
    }

    public function categories_update( Request $request ){
            $rules = RuleFactory::make([
                '%page_name%' => 'required|string',
            ]);

            // $validatedData = $request->validate($rules);

            $data=$request->all();
            $page = (Pages::where('id',3)->first() ? Pages::where('id',3)->first() : new Pages);

            if($request->hasFile('main_image_path')){
                $request->main_image_path->move(public_path('images'), $request->main_image_path->getClientOriginalName());
                $main_image_path='images/'.$request->main_image_path->getClientOriginalName();
            }
            $data['main_image_path']= (!empty($main_image_path) ?  $main_image_path : null );

            if($request->hasFile('second_image_path')){
                $request->second_image_path->move(public_path('images'), $request->second_image_path->getClientOriginalName().time());
                $second_image_path='images/'.$request->second_image_path->getClientOriginalName();
            }
            $data['second_image_path']= (!empty($second_image_path) ?  $second_image_path : null );
            try{
                $page->update($data);
                }catch(Exception $e){
                    return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
                }
            return response()->json(['success' => true,'data'=>new PageResource($page)], 200);
    }


    public function add_page(Request $request)
    {
        $data=$request->all();
        Pages::create([
            'page_name' =>$data['page_name'] ,
            'slug' => str_replace(' ','-',$data['page_name']),
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        try{
            $data['user_articles']= ArticleResource::collection(Article::whereHas('user' , function($query) use($search){
                $query->where('name','like','%' . $search . '%');
            })->get());
            $data['articles']= ArticleResource::collection(Article::WhereTranslationLike('title', '%' . $request->search . '%')->get());
            $data['users']= UserResource::collection(User::where('name','like','%' . $request->search . '%')->role('author')->get());
        }catch(Exception $e){
                return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
        return response()->json(['success' => true,'data'=>$data], 200);
    }
}