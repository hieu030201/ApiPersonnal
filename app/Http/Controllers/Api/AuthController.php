<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //danh sach user
    public function listUser()
    {
        $users = User::all();
        return response()->json([
            'code'=>200,
            'data'=>$users,
        ]);
    }
    
    // dang ky 1 tai khoan moi tu phia admin
    public function register(Request $request){
        $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|string|min:5|max:100|unique:users',
            'phone'=> 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15|unique:users',
            'username'=>'required|unique:users',
        ]);
        $avatar = $request->file('avatar');
        if($request->hasFile('avatar')){
            $new_name = rand().'.'.$avatar->getClientOriginalExtension();
            $avatar->move(public_path('/uploads/avatars'),$new_name);
        }
        else{
            $avatar="noavatar";
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dateOfBirth' => $request->dateOfBirth,
            'avatar' => $new_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            // $input['password_hash']=hash('sha256', ($request->password).($pass));
            // $input['password']=$pass;
            'phone' => $request->phone,
            'level_id' => $request->level_id,
            'status' => $request->status,
            'follow' => $request->follow,
        ]);
      
        $token = $user->createToken('auth_Token')->plainTextToken;
        return response()->json([
            'code'    => 200,
            'data'    =>  $user,
            'message' => 'User has been register!',
            'token'   =>  $token,
        ],200); 

    }
    
    //dang nhap phia user vs admin
    public function login(Request $request)
    {
        $credentials = request(['email','password']);
        if(Auth::attempt($credentials))
        {
            $user = User::where('email', $request['email'])->firstOrFail();

            $token = $user->createToken('auth_Token')->plainTextToken;
            
            return response()->json([
                'user' => Auth::user(),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
           
        }else{
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized!',
            ],401);
        }
    }
    
    //dang xuat phia user vs admin
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );
    }

    public function editUser(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|string|min:5|max:100|unique:users',
            'phone'=> 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15|unique:users',
            'username'=>'required|unique:users',
        ]);
        $avatar = $request->file('avatar');
        if($request->hasFile('avatar')){
            $new_name = rand().'.'.$avatar->getClientOriginalExtension();
            $avatar->move(public_path('/uploads/avatars'),$new_name);
        }
        else{
            $avatar="noavatar";
        }
        $user = User::find($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dateOfBirth' => $request->dateOfBirth,
            'avatar' => $new_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            // $input['password_hash']=hash('sha256', ($request->password).($pass));
            // $input['password']=$pass;
            'phone' => $request->phone,
            'level_id' => $request->level_id,
            'status' => $request->status,
            'follow' => $request->follow,
        ]);
      
        $token = $user->createToken('auth_Token')->plainTextToken;
        return response()->json([
            'code'    => 200,
            'data'    =>  $user,
            'message' => 'User has been register!',
            'token'   =>  $token,
        ],200); 

    }

    public function activeFollow($id)
    {
        $user = User::find($id)->get();
        if(!$user)
        {
            return response()->json(['message'=>'Follow Fail!']);
        }else
        {
            $user = User::where('id', $id)->update(['follow'=>'active']);
        }
        return response()->json([
            'code'=>200,
            'message'=>'active follow successfully'
        ]);
    }
    
    //lay user phia admin
    public function getUser($id)
    {
        $users = User::find($id);
        return response()->json([
            'code'=>200,
            'data'=>$users,
        ],200);
    }

    public function searchUser(Request $request)
    {
        $search = $request->search;
        $data = User::where('name','LIKE','%'.$search.'%' || 'email','LIKE','%'.$search.'%');
        return response()->json([
            'code' => 200,
            'message' => 'search results',
            'data' => $data,
        ]);
    }

    //xem thong tin tu phia nguoi login
    public function me(Request $request)
    {
        return $request->user();
    }
}
