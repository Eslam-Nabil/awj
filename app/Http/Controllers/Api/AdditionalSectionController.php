<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Models\AdditionalSection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdditionalSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'page_id' =>'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }

        if($request->hasFile('image_path')){
            $request->image_path->move(public_path('images'), $request->image_path->getClientOriginalName());
            $image_path='images/'.$request->image_path->getClientOriginalName();
        }
        try{
        $section = AdditionalSection::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path'=> (!empty($image_path) ?  $image_path : null ),
            'pages_id'=>$request->page_id
        ]);
        }catch(Exception $e){
            return response()->json(['success' => false,'message'=>"There is something wrong","error"=>$e->getMessage()], 401);
        }

        return response()->json(['success' => true,'message'=>"Section added successfully"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdditionalSection  $additionalSection
     * @return \Illuminate\Http\Response
     */
    public function show(AdditionalSection $additionalSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdditionalSection  $additionalSection
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdditionalSection  $additionalSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $section=AdditionalSection::find($id);

        if($section){
            if($request->hasFile('image_path')){
                $request->image_path->move(public_path('images'), $request->image_path->getClientOriginalName());
                $image_path='images/'.$request->image_path->getClientOriginalName();
            }
                $section->title=$request->title;
                $section->description=$request->description;
                $section->image_path =(!empty($image_path) ?  $image_path : null );
                $section->save();
        }else{
            return response()->json(['success'=>false,'message'=>"section not found"],401);
        }
            return response()->json(['success'=>true ,'message'=>"section updated deleted successfully"],200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdditionalSection  $additionalSection
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section=AdditionalSection::find($id);
        if($section){
            $section->delete();
        }else{
            return response()->json(['success'=>false,'message'=>"section is not found"],401);
        }
        return response()->json(['success'=>true,'message'=>"section deleted successfully"],200);

    }
}