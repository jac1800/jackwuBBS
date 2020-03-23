<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function root()
    {
         return view("pages.root");
    }

    public function info()
    {
        echo phpinfo();
    }

    public function permissionDenied()
    {
        // 如果当前用户有权限访问后台，直接跳转访问
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }
        // 否则使用视图
        return view('pages.permission_denied');
    }

    public function testRedis(\Redis $redis)
    {
        $redis->connect(env("REDIS_HOST"),env("REDIS_PORT"));
        //dd($rs);
        $redis->set('name', 'jackwu');
        $values =$redis->get('name');
        dd($values);    //输出："jackwu"
        //加一个小例子比如网站首页某个人员或者某条新闻日访问量特别高，可以存储进redis，减轻内存压力
        $userinfo = Member::find(1200);
        $redis->set('user_key',$userinfo);
        if(\Redis::exists('user_key')){
            $values = \Redis::get('user_key');
        }else{
            $values = Member::find(1200);//此处为了测试你可以将id=1200改为另一个id
        }
        dump($values);
    }
}
