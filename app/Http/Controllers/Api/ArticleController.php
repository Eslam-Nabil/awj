<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CertificateRequest;
use App\Models\UserArticle;
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

    public function approved_articles($lang)
    {
        Config::set('translatable.locale', $lang);
        $articles = ArticleToPublishResource::collection(Article::where('isApproved',1)->get());
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
        })->where('isApproved',1)->get());
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

        $data=$request->except('task');
        $tasks=$request->task;
        $data['user_id']=$userid;
        $data['serial_number']=time();
        if($request->price != '0'){
            $data['status']='premium';
        }else{
            $data['status']='free';
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
        $articles =  BoughtArticlesResource::collection($user->articles->where('pivot.order_status','completed'));
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
            $user= Auth::user();
            DB::beginTransaction();
            if($request->has('article_id')){
                $exist = $user->articles()->where(['article_id'=>$request->article_id,'order_status'=>'completed'])->first();
                if($exist){
                    return response()->json(['success' => false,'message'=>'You cannot buy article twice'], 400);
                }
                $article = Article::find($request->article_id);
                if($article->price == '0.00'){
                    $data=[
                        'is_free'=>1,
                        'price'=>$article->price,
                        'order_status'=>'completed',
                    ];
                    $user->articles()->attach([$request->article_id],$data);
                    event(new BuyArticle($request->article_id, $user));
                    DB::commit();
                    return response()->json(['success' => true,'message'=>'successfully action check projects for tasks'], 200);
                }else{
                    return response()->json(['success' => false,'message'=>'This article is not free please add it to bag'], 400);
                }
            }

            if($request->has('articles')){
                $paypal_request=[];

                $exist = $user->articles()->whereIn('article_id',$request->articles)->where('order_status','completed')->exists();
                if($exist){
                    return response()->json(['success' => false,'message'=>'You cannot buy article twice'], 400);
                }

                $articles = Article::whereIn('id',$request->articles)->get();
                foreach($articles as $article){
                    $paypal_request[]=[
                        'title'=>$article->title,
                        'price'=>$article->price
                    ];
                }
                $order_result= $this->paypal_order_article($paypal_request);
                foreach($articles as $article){
                    $data=[
                        'is_free'=>0,
                        'price'=>$article->price,
                        'order_status'=>'inProgress',
                        'order_id'=>$order_result->id
                    ];
                    $user->articles()->attach([$article->id],$data);
                }
                    DB::commit();
                    return response()->json(['success' => true,'link'=>$order_result->links[1]], 200);
            }
        }catch(Exception $e){
             return response()->json(['success' => false,'message'=>$e->getMessage()], 400);
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

    public function attach_certificate(CertificateRequest $request)
    {
        try{
            DB::beginTransaction();
            $article = UserArticle::where('article_id',$request->article_id)->get();
            if($request->hasFile('certificate')){
                $certificate=$this->storeFile($request->certificate,'articles');
            }
            $certificate_path= (!empty($certificate) ?  $certificate : null );
            $article->certificate = $certificate_path;
            $article->save();
            DB::commit();
            return response()->json(['success' => true,'message'=>'certificate added successfully'], 200);
         }catch(Exception $e){
             return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
         }

    }

}
