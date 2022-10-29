<?php

namespace App\Http\Controllers\Api;

use App\Models\SectionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;

class SectionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types= TypeResource::collection(SectionType::all());
        return response()->json(['success' => true,'data'=>$types], 200);
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
        $section = SectionType::create([
            'name' => $request->name
        ]);
        return response()->json(['success' => true,'message'=>"type added successfully"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section_type  $section_type
     * @return \Illuminate\Http\Response
     */
    public function show(Section_type $section_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section_type  $section_type
     * @return \Illuminate\Http\Response
     */
    public function edit(Section_type $section_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section_type  $section_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section_type $section_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section_type  $section_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section_type $section_type)
    {
        //
    }
}