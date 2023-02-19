<?php

namespace App\Http\Controllers\Api;

use Exception;
use Rules\Password;
use App\Models\User;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use UploadFile;
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
    public function register(StoreUser $request)
    {
        $validator = $request->validated();
    try{
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone'=>$request->phone,
            'emirateid'=>$request->emirateid,
            'birthdate'=>$request->birthdate,
            'password' => Hash::make($request->password),
        ]);
        if($request->role=='author'){
            $user->assignRole('author');
        }elseif($request->role=='publisher'){
            $user->assignRole('publisher');
        }elseif($request->role=='reviewer'){
            $user->assignRole('reviewer');
        }else{
            $user->assignRole('student');
        }
        $token = $user->createToken('logintoken')->accessToken;
    }catch(\Exception $e){
        return response()->json([ 'success' => false,'error'=>$e->getMessage()], 401);
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
        try{
        $user=User::findOrfail($id);
        $ifStudent = $user->hasRole('student');
            if($ifStudent){
                return response()->json(['success' => true,'data'=>new UserResource($user)], 200);
            }else{
                return response()->json(['success' => false,'message'=>'You dont have permission to see this user'], 403);
            }
        }catch(Exception $e){
            return response()->json(['success' => false,'message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {          
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:6|string'
        ]);
        if (!Hash::check($request->current_password, Auth::user()->password)) 
        {
            return response()->json([ 'success' => false,'message'=>'current password is not correct'], 500);
        }
        $user =  User::find(Auth::id());
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json([ 'success' => true,'message'=>"Password Changed Successfully"], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        $validator = $request->validated();
        if(Auth::check()){
            $user_id = Auth::id();
        }else{
            return response()->json([ 'success' => false,'error'=>'Unauthorized User'], 401);
        }
        try{
            $user=User::find($user_id);
            $user->update($request->all());
        }catch(Exception $e){
            return response()->json([ 'success' => false,'error'=>$e->getMessage()], 401);
        }
        return response()->json([ 'success' => true,'data'=>new UserResource($user)], 200);
    }

    public function updateProfilePicture(Request $request)
    {
        try{
            $user=Auth::user();
                $picture_path=$this->storeFile($request->picture,'users');
            $user->picture = $picture_path;
            $user->save();
        }catch(Exception $e){
            return response()->json([ 'success' => false,'error'=>$e->getMessage()], 401);
        }
        return response()->json([ 'success' => true,'data'=>new UserResource($user)], 200);
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
