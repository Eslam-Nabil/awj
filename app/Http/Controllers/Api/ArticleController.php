<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\Paypal;
use App\Models\Article;
use App\Events\BuyArticle;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TasksResource;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\UserTaskResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\BoughtArticlesResource;
use App\Http\Resources\ArticleToPublishResource;
use Astrotomic\Translatable\Validation\RuleFactory;

class ArticleController extends Controller
{
    use UploadFile,Paypal;
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

    public function articlesToPublish()
    {
        $articles = ArticleToPublishResource::collection(Article::where('isApproved',0)->get());
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

    public function getUserBoughtArticles($lang)
    {
        Config::set('translatable.locale', $lang);
        $user = Auth::user();
        $articles =  BoughtArticlesResource::collection($user->articles);
        return response()->json(['success' => true,'data'=>$articles], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $article=Article::findOrFail($request->article_id);
            $article->delete();
        }catch(Exception $e){
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
        return response()->json(['success' => true,'message'=>'article Deleted successfully'], 200);
    }

    public function buyArticle(Request $request)
    {
        try{
            DB::beginTransaction();
            $article = Article::find($request->article_id);
            $paypal_request=[
                'title'=>$article->title,
                'price'=>$article->price
            ];
            if($article->price == '0'){
                $isFree = 1;
            }else{
                $isFree = 0;
            }
            $order_result= $this->paypal_order_article($paypal_request);
            $data=[
                'is_free'=>$isFree,
                'price'=>$article->price,
                'order_status'=>'inProgress',
                'order_id'=>$order_result->id
            ];
            $user= User::find(Auth::id());
            $user->articles()->attach([$request->article_id],$data);
            DB::commit();
            return response()->json(['success' => true,'link'=>$order_result->links[1]], 200);
            return response()->json(['success' => true,'message'=>'successfully action check projects for tasks'], 200);
        }catch(Exception $e){
            dd($e);
        }
    }

    public function articleTasks($lang,$articleId)
    {
        if (Auth::check()) {
            $userid=Auth::id();
        }else{
            return response()->json(['success' => false,'error'=>'Unauthorized'], 500);
        }

        Config::set('translatable.locale', $lang);
        try{
            $user=User::findOrFail($userid);
            //Magic method
            $tasks= $user->tasks()->whereArticleId($articleId)->get();
            return response()->json(['success' => true,'tasks'=>UserTaskResource::collection($tasks)], 200);
        }catch(Exception $e){
            return response()->json(['success' => false,'message'=>'There is something wrong','error'=>$e->getMessage()], 500);
        }
    }
    public function approve(Request $request)
    {
        try{
            $article=Article::findOrFail($request->article_id);
            $article->isApproved = 1;
            $article->save();
        }catch(Exception $e){
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
        return response()->json(['success' => true,'data'=>new ArticleResource($article)], 200);
    }
    public function search(Request $request)
    {
        try{
           $articles= ArticleResource::collection(Article::WhereTranslationLike('title', '%' . $request->search . '%')->get());
        }catch(Exception $e){
                return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }
        return response()->json(['success' => true,'data'=>$articles], 200);
    }
    
}
