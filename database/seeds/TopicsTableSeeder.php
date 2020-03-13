<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        //获取userid
        $user_id=User::all()->pluck("id")->toArray();
        //分类ID
        $cat_id=Category::all()->pluck("id")->toArray();
        $faker =app(Faker\Generator::class);

        $topics =factory(Topic::class)
            ->times(100)
            ->make()
            ->each(function ($topic,$index) use ($faker,$user_id,$cat_id) {
                $topic->user_id=$faker->randomElement($user_id);
                $topic->category_id=$faker->randomElement($cat_id);
            });


//        $topics = factory(Topic::class)->times(50)->make()->each(function ($topic, $index) {
//            if ($index == 0) {
//                // $topic->field = 'value';
//            }
//        });

        Topic::insert($topics->toArray());
    }

}

