<?php


namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class HeaderComposer
{
    protected $key_prefix="view_composer";

    public function compose(View $view)
    {
//        $categories=$this->cache("categories",function(){
//            return Category::all();
//        },60);

        $key=$this->key_prefix.":categories";
//        $categories=Cache::get($key,function () use ($key){
//            $categories=Category::all();
//            Cache::put($key,$categories,60);
//            return $categories;
//        });
        $categories=Cache::remember($key,10,function (){
            return Category::all();
        });
        $view->with("categories",$categories);
    }

    public function cache($key,$callback,$timeout=60)
    {
        $key =$this->key_prefix.":".$key;
        //Cache::forget($key);
        if(Cache::has($key)){
            $return_array =Cache::get($key,function (){
                return Category::all();
            });
        }else{
            $return_array =$callback();
            if($return_array){
                Cache::put($key,$return_array,$timeout);
            }else{
                $return_array=[];
            }
        }


        return $return_array;
    }
}