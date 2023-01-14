<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TasksResource;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\SubmitTaskRequest;
use App\Http\Resources\UserTaskResource;

class TaskController extends Controller
{
    use UploadFile;
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
    public function submitTask(SubmitTaskRequest $request)
    {
        if (Auth::check()) {
            $user=Auth::user();
        }else{
            return response()->json(['success' => false,'error'=>'Unauthorized'], 500);
        }

           try{
            $task = $user->tasks->where('pivot.task_id',$request->task_id)->first();
            (Carbon::now()->gt($task->pivot->delivery_date)  ? $delay = 1 : $delay = 0);
            if($request->hasFile('file_path')){
                $task_file_path=$this->storeFile($request->file_path,'tasks');
            }
            $task->pivot->update([
                'delay'=>$delay,
                'file_path'=>$task_file_path,
                'student_comment' => $request->student_comment,
                'delivered_date' => date('d-m-Y', strtotime(time())),
                'status'=>'inProgress'
            ]);

            return response()->json(['success' => true,'data'=>new  UserTaskResource($task)], 200);
        }catch(Exception $e){
            return response()->json(['success' => true,'error'=>$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
    public function UserTasks($lang)
    {
        if (Auth::check()) {
            $userid=Auth::id();
        }else{
            return response()->json(['success' => false,'error'=>'Unauthorized'], 500);
        }
        Config::set('translatable.locale', $lang);
        try{
            $user=User::findOrFail($userid)->whereHas('tasks')->first();
            //  return $user;

            $tasks= $user->tasks()->get();


            return response()->json(['success' => true,'tasks'=>UserTaskResource::collection($tasks)], 200);
        }catch(Exception $e){
            return response()->json(['success' => false,'error'=>$e->getMessage()], 500);
        }

    }
}
