<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    //
    public function show(User $user)
    {
         return view("users.show",compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
          //使用request的Validate来验证
//        $validata=$request->validate([
//            "name"=>"required|max:255",
//            "email"=>"required|max:128",
//            "instruction"=>"required|max:255",
//        ]);
        //自定义请求验证UserRequest
        $rs=$user->update($request->all());
        if($rs){

        }else{

        }
        return redirect()->route("users.show",$user->id)->with('success',"编辑个人资料成功");

    }

    public function edit(User $user)
    {
        return view("users.edit",compact('user'));
    }

}
