<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentsResource;
use Exception;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments=CommentsResource::collection(Comment::all());
        return response()->json(['success' => true,'data'=>$comments], 200);
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
        if(Auth::check()) {
            $userid=Auth::id();
        }else{
               return response()->json(['success' => false,'error'=>'Unauthorized'], 401);
            }
        $validator=Validator::make($request->all(),[
            'article_id'         =>'required|integer',
            'comment_text'   =>'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }

        $data=$request->all();
        $data['user_id']=$userid;

        try{
            $comment=Comment::create($data);
        }catch(Exception $e){
               return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
            }
        return response()->json(['success' => true,'data'=>$comment], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment=Comment::find($id);
        if($comment){
            $comment->delete();
        }else{
            return response()->json(['success'=>false,'message'=>"Comment is not found"],404);
        }
        return response()->json(['success'=>true,'message'=>"Comment deleted successfully"],200);
    }
       /**
     * approve the specified resource to appar on front.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function approveToShow($id)
    {
        $comment=Comment::find($id);
        if($comment){
            $comment->show = 1;
            $comment->save();
        }else{
            return response()->json(['success'=>false,'message'=>"Comment is not found"],404);
        }
        return response()->json(['success'=>true,'message'=>"Comment approved successfully"],200);
    }
}
