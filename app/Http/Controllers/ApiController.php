<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6|'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:4|'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = \JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
        return $this->createNewToken($token);
    }

    public function chatRoom($id)
    {
        $users = User::where('id', $id)->first();
        $currentUser = Auth::user()->id;
        $user1 = "{$currentUser}";
        $user2 = "{$users->id}";
        if (ord($user1[0]) > ord($user2[0])) {
            $roomId = $user1 . $user2;
        } else {
            $roomId = $user2 . $user1;
        }
        $messages = Message::where('roomId', $roomId)->get();
        return response()->json([
            'roomId' => $roomId,
            'currentUser' => $currentUser,
            'messages' => $messages
        ], 201);

    }
    public function createNewToken($token)
    {
        return response()->json([
            'status' => '200',
            'message' => 'Login Successfull',
            'userName' => auth()->user()->name,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ],200);
    }
    public function storeChat(Request $request){
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'roomId' => 'required',
            'currentUser' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $message = $request->message;
        $roomId = $request->roomId;
        $currentUser = $request->currentUser;
        $data = new Message();
        $data->roomId = $roomId;
        $uuid = Str::uuid();
        $data->uuid =  $uuid;
        $data->userCurrentId = $currentUser;
        $data->message =$message;
        $save =$data->save();
        return response()->json([
            'message' => 'Message send successfully',
        ], 201);


    }
    public function delete($id){
        Message::where('uuid',$id)->delete();
        return response()->json([
            'message' => 'Message deleted successfully',
        ], 201);

   }
   public function deleteTemp($id){
    Message::where('uuid',$id)->update(['status' => 'In-Active']);
   return redirect()->back();

}
}
