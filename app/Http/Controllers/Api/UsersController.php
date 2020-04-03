<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;


class UsersController extends Controller
{
    //
    public function store(UserRequest $request)
    {
        $verifiData=Cache::get($request->verification_key);
        if(!$verifiData){
            abort(403,"验证码失效");
        }

        if(!hash_equals($verifiData['code'],$request->verification_code)){
            throw new AuthenticationException('验证码错误');
        }
        $user=User::create([
            'name'=>$request->name,
            'phone'=>$verifiData['phone'],
            'password'=>$request->password,
        ]);
        Cache::forget($request->verification_key);
        return new UserResource($user);
    }

    public function show(User $user, Request $request)
    {
        return new UserResource($user);
    }

    public function me(Request $request)
    {
        return (new UserResource($request->user()))->showSensitiveFields();
    }
}
