<?php

namespace App\Http\Controllers\Api;

use exception;
use Rules\Password;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allUsers=UserResource::collection(User::with('roles')->get());
        return response()->json(['success' => true,'data'=>$allUsers], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user=Auth::user();
            $token = $user->createToken('logintoken')->accessToken;
            return response()->json(['success' => true,'data'=>['token'=>$token,'user'=>new UserResource($user)]], 200);
        }
        else{
            return response()->json(['message' => "Incorrect Details. Please try again", 'success' => false], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Ill uminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name' =>'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric|digits:10|unique:users',
            'emirateid' => 'required|unique:users',
            'birthdate' => 'required|date',
            'password' =>'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
    try{
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone'=>$request->phone,
            'emirateid'=>$request->emirateid,
            'birthdate'=>$request->birthdate,
            'password' => Hash::make($request->password),
        ]);
        if($request->role=='student'){
            $user->assignRole('student');
        }else{
            $user->assignRole('author');
        }
        $token = $user->createToken('logintoken')->accessToken;
    }catch(\Exception $e){
        return response()->json([ 'success' => false,'error'=>$e], 401);
    }
    return response()->json([ 'success' => true,'data'=>new UserResource($user)], 200);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user= new UserResource(User::where('id',$id)->with('roles')->first());
        return response()->json(['success' => true,'data'=>$user], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::find($id);
        if($user){
            $user->delete();
        }else{
            return response()->json(['status'=>'401','message'=>"User is not found"]);
        }
        return response()->json(['status'=>'200','message'=>"User deleted successfully"]);




    }
    public function test($id)
    {
        $user=User::find($id);
        $data=$user->getAllPermissions();

        return response()->json(['status'=>'200','data'=>$user]);

    }
}
