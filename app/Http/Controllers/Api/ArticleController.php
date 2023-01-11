<?php

namespace App\Http\Controllers\Api;

use App\Events\BuyArticle;
use Exception;
use App\Models\Article;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Models\User;
use Astrotomic\Translatable\Validation\RuleFactory;
use Carbon\Carbon;

class ArticleController extends Controller
{
    use UploadFile;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang)
    {
        Config::set('translatable.locale', $lang);
        $articles = ArticleResource::collection(Article::get());
         return response()->json(['success' => true,'data'=>$articles], 200);
    }
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getArticlesByUser($lang)
    {
        Config::set('translatable.locale', $lang);
        $user_id=Auth::id();
        $articles = ArticleResource::collection(Article::whereHas('user',function($q) use ($user_id) {
            $q->where('id',$user_id);
        })->get());
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
        // $data=$request->all();
        $data=$request->except('task');
        // $tasks=$data['task'];
        $tasks=$request->task;
        $data['user_id']=$userid;
        $data['serial_number']=time();
        if($request->price != '0'){
            
        }

        if($request->hasFile('article_file_path')){
           $article_file_path=$this->storeFile($request->article_file_path,'articles');
        }
        $data['article_file_path']= (!empty($article_file_path) ?  $article_file_path : null );

        if($request->hasFile('audio_file_path')){
            $audio_file_path=$this->storeFile($request->audio_file_path,'articles');
        }
        $data['audio_file_path']= (!empty($audio_file_path) ?  $audio_file_path : null );

        if($request->hasFile('cover_file_path')){
            $cover_file_path=$this->storeFile($request->cover_file_path,'articles');
        }
        $data['cover_file_path']= (!empty($cover_file_path) ?  $cover_file_path : null );

        try{
            DB::beginTransaction();
            $article=Article::create($data);
            if($request->has_task){
                foreach($tasks as $task){
                    if(array_key_exists('file_path',$task)){
                        $file_path=$this->storeFile($task['file_path'],'tasks');
                     }
                     $task['file_path']=$file_path ?? null;
                    $article->tasks()->create($task);
                }
            }
            DB::commit();
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
    public function show($lang,$article)
    {
        Config::set('translatable.locale', $lang);
        try{
            $article=Article::findOrFail($article);
        }catch(Exception $e){
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
        return response()->json(['success' => true,'data'=>new ArticleResource($article)], 200);
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

    public function buyArticle(Request $request)
    {
        if (Auth::check()) {
            $userid=Auth::id();
           }else{
               return response()->json(['success' => false,'error'=>'Unauthorized'], 500);
           }
        try{
            DB::beginTransaction();
            if($request->price == '0'){
                $isFree = 1;
            }else{
                $isFree = 0;
            }
            $data=[
                'is_free'=>$isFree,
                'price'=>$request->price
            ];
            $user= User::find($userid);
            $user->articles()->attach([$request->article_id],$data);
            event(new BuyArticle($request->article_id, $user));

            DB::commit();
            return response()->json(['success' => true,'message'=>'successfull action check projects for tasks'], 200);
        }catch(Exception $e){

            dd($e);
        }
    }
}
