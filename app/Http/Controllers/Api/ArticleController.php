<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
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
        //
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
    public function store(Request $request)
    {
        // $userid=Auth::id();
        $userid=1;
        $rules = RuleFactory::make([
            '%title%'             => 'required|string',
            '%description%'       => 'required|string',
            'category_id'         =>'required|integer',
            'article_file_path'   =>'required|mimes:pdf',
            'audio_file_path'     =>'required|mimes:audio/mpeg,mp3',
            'cover_file_path'     =>'required|mimes:jpg,png',
            'price'               =>'required|integer',
        ]);

        // $validatedData = $request->validate($rules);

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
               return response()->json(['success' => false,'error'=>$e->getMessages()], 500);
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
