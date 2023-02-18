<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\NewsLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsLetterRequest;
use App\Http\Resources\NewsLetterResource;

class NewsLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = NewsLetter::all();
        return response()->json(['success'=>true,'data'=>NewsLetterResource::collection($emails)],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsLetterRequest $request)
    {
        try {
           NewsLetter::create(['email'=>$request->email]);
           return response()->json(['success'=>true,'message'=>'Email Added Successfully'],200);
        } catch (Exception $e) {
            return response()->json(['success'=>false,'error'=>$e->getMessage()],200);
           
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function show(NewsLetter $newsLetter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $email=NewsLetter::find($request->id);
        $email->delete();
    }
}
