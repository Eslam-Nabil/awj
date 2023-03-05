<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;

class SettingController extends Controller
{
    
    public function index()
    {
        $sections=SettingResource::collection(Setting::all());
        return response()->json(['success' => true,'data'=>$sections], 200);
    }
    public function store(Request $request)
    {
        
    }
    public function edit(Request $request)
    {
        $setting = Setting::find($request->id);
        $setting->value = $request->value;
        $setting->save();
        return response()->json(['success' => true,'data'=>$setting], 200);
         
    }
}
