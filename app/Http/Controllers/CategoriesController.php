<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;
use App\Models\Link;


class CategoriesController extends Controller
{
    //
    public function show(Category $category, Request $request , Topic $topic,User $user,Link $link)
    {
        if(isset($request->order)){
            $topics=$topic->whereOrder($request->order)->with("category","user")->where("category_id",$category->id)->paginate(15);
        }else{
            $topics=$topic->with("category","user")->where("category_id",$category->id)->paginate(15);
        }

        //活跃用户
        $active_users=$user->getActiveUsers();
        //资源链接
        $links=$link->getAllCached();
        return view("topics.index", compact('topics', 'category','active_users','links'));
    }
}
