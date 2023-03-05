<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;

class SettingController extends Controller
{
    use UploadFile ;
    public function index()
    {
        $settings=SettingResource::collection(Setting::all());
        return response()->json(['success' => true,'data'=>$settings], 200);
    }
    public function store(Request $request)
    {
        
    }
    public function edit(Request $request)
    {
        $setting = Setting::find($request->id);
        if($request->hasFile('value')){
           $value= $this->storeFile($request->value,'images');
           $setting->value = $value;
        }else{
            $setting->value = $request->value;
        }
        $setting->save();
        return response()->json(['success' => true,'data'=>new SettingResource($setting)], 200);
         
    }
}
