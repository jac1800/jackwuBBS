<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;


class CategoriesController extends Controller
{
    //
    public function show(Category $category, Request $request , Topic $topic)
    {
        if(isset($request->order)){
            $topics=$topic->whereOrder($request->order)->with("category","user")->where("category_id",$category->id)->paginate(15);
        }else{
            $topics=$topic->with("category","user")->where("category_id",$category->id)->paginate(15);
        }
        return view("topics.index", compact('topics', 'category'));
    }
}
