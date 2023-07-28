<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function storeChat(Request $request){
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
        if($save){
            return response()->json(['success' => 'Success','uuid'=>$uuid]);
        }


    }
    public function delete($id){
         Message::where('uuid',$id)->delete();
        return redirect()->back();

    }
    public function deleteTemp($id){
         Message::where('uuid',$id)->update(['status' => 'In-Active']);
        return redirect()->back();

    }
}
