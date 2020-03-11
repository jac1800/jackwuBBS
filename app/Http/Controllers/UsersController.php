<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImagesUploadHandles;

class UsersController extends Controller
{
    //
    public function show(User $user)
    {
         return view("users.show",compact('user'));
    }

    public function update(UserRequest $request, User $user,ImagesUploadHandles $img_upload)
    {
          //使用request的Validate来验证
//        $validata=$request->validate([
//            "name"=>"required|max:255",
//            "email"=>"required|max:128",
//            "instruction"=>"required|max:255",
//        ]);
        //自定义请求验证UserRequest
        $data=$request->all();

        if($request->avatar) {
            $upload_rs = $img_upload->save($data["avatar"], 'avatar', $user->id);
            if ($upload_rs) {
                $data["avatar"] = $upload_rs['path'];
            }
        }
        $rs=$user->update($data);
        return redirect()->route("users.show",$user->id)->with('success',"编辑个人资料成功");

    }

    public function edit(User $user)
    {
        return view("users.edit",compact('user'));
    }

}
