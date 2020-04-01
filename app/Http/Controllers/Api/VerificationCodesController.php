<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    //
    public function store(VerificationCodeRequest $request,EasySms $easySms)
    {
        //先验证图片验证码再发送短信
        $capthaData=Cache::get($request->captcha_key);
        if(!$capthaData){
            abort(403,"图片验证码超时");
        }
       // dd($capthaData['code'],$request->captcha_code);

        if(!hash_equals($capthaData['code'],strtolower($request->captcha_code))){
            Cache::forget($request->captcha_key);
            throw new AuthenticationException("图片验证码失败");
        }

        $phone=$capthaData['phone'];
        if(!app()->environment('production')){
            $code='1234';
        }else{
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            try{
                $result=$easySms->send($phone,[
                    "template"=>config("easysms.gateways.aliyun.templates.register"),
                    "data"=>['code'=>$code],
                ]);

            }catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception){
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $key="verificationCode_".Str::random(15);
        $expiredAt=now()->addMinute(5);
        // 缓存验证码 5 分钟过期。
        Cache::put($key,["phone"=>$phone,"code"=>$code],$expiredAt);

        return response()->json([
            "key"=>$key,
            "expired_at"=>$expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
