<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //SQL打印调试
//        DB::listen(function ($query){
//            echo $query->sql."<br>";
//            //$query->bindings
//            //$query->time
//        });
        //共享数据
//        view()->composer("*",function($view){
//            $view->with("categories",Category::all());
//        });

    }
}
