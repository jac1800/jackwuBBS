<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;

class RepliesTableSeeder extends Seeder
{
    public function run()
    {

        $user_id=User::all()->pluck("id")->toArray();
        $topic_id=\App\Models\Topic::all()->pluck("id")->toArray();
        $faker=app(Faker\Generator::class);
        $replies = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index) use ($user_id,$topic_id,$faker) {
              $reply->user_id =$faker->randomElement($user_id);
              $reply->topic_id=$faker->randomElement($topic_id);
        });
        Reply::insert($replies->toArray());
    }
}

