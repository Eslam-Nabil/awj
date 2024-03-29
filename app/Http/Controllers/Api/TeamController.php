<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Front\TeamResource as FrontTeamResource;
use App\Http\Resources\TeamResource;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TeamResource::collection(Team::all());
        return response()->json(['success'=>true,'data'=>$data],200);
    }
    public function front_index($lang)
    {
        Config::set('translatable.locale', $lang);
        $data = FrontTeamResource::collection(Team::all());
        return response()->json(['success'=>true,'data'=>$data],200);
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
        $data=$request->all();
        if($request->hasFile('picture_path')){
            $request->picture_path->move(public_path('images'), $request->picture_path->getClientOriginalName());
            $picture_path='images/'.$request->picture_path->getClientOriginalName();
        }
        $picture_path= (!empty($picture_path) ?  $picture_path : null );
        $data['picture_path']=$picture_path;
        $team=Team::create($data);
        // $validator=Validator::make($request->all(),[
        //     'name' =>'required|string',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        // }

        // try{
        //     $team=Team::create([
        //         'name' => $request->name,
        //         'job_title' => $request->job_title,
        //         'picture_path'=> (!empty($picture_path) ?  $picture_path : null ),
        //     ]);
        // }catch(Exception $e){
        //     return response()->json(['success' => false,'message'=>"There is something wrong",'error'=>$e->getMessage()], 500);
        // }
            return response()->json(['success' => true,'message'=>"Team Member added successfully",'data'=>new TeamResource($team)], 200);
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
        $member=Team::find($id);
        $data=$request->all();
        if($member){
            if($request->hasFile('picture_path')){
                $request->picture_path->move(public_path('images'), $request->picture_path->getClientOriginalName());
                $picture_path='images/'.$request->picture_path->getClientOriginalName();
            }
            $picture_path= (!empty($picture_path) ?  $picture_path : null );
            $data['picture_path']=$picture_path;
            $member->update($data);

        }else{
            return response()->json(['success'=>false ,'message'=>"Team Member not found"],401);
        }
            return response()->json(['success'=>true , 'message'=>"Team Member updated successfully","data"=>new TeamResource($member)],200);
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
        $member=Team::find($id);
        if($member){
            $member->delete();
        }else{
            return response()->json(['status'=>'401','message'=>"Team Member is not found"]);
        }
        return response()->json(['status'=>'200','message'=>"Team Member deleted successfully"]);

    }
}
