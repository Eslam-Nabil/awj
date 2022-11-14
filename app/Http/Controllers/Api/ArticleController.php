<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use Astrotomic\Translatable\Validation\RuleFactory;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $articles = ArticleResource::collection(Article::get());
        return response()->json(['success' => true,'data'=>$articles], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        if (Auth::check()) {
         $userid=Auth::id();
        }else{
            return response()->json(['success' => false,'error'=>'Unauthorized'], 500);
        }
        // $validatedData = $request->validate($rules);
        $validated = $request->validated();
        $data=$request->all();
        $data['user_id']=$userid;

        if($request->hasFile('article_file_path')){
            $request->article_file_path->move(public_path('articles'), $request->article_file_path->getClientOriginalName());
            $article_file_path='articles/'.$request->article_file_path->getClientOriginalName();
        }
        $data['article_file_path']= (!empty($article_file_path) ?  $article_file_path : null );

        if($request->hasFile('audio_file_path')){
            $request->audio_file_path->move(public_path('articles'), $request->audio_file_path->getClientOriginalName());
            $audio_file_path='articles/'.$request->audio_file_path->getClientOriginalName();
        }
        $data['audio_file_path']= (!empty($audio_file_path) ?  $audio_file_path : null );

        if($request->hasFile('cover_file_path')){
            $request->cover_file_path->move(public_path('articles'), $request->cover_file_path->getClientOriginalName());
            $cover_file_path='articles/'.$request->cover_file_path->getClientOriginalName();
        }
        $data['cover_file_path']= (!empty($cover_file_path) ?  $cover_file_path : null );

        try{
             $article=Article::create($data);
        }catch(Exception $e){
               return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
            }
        return response()->json(['success' => true,'data'=>new ArticleResource($article)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}