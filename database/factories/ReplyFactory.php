<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {
    return [
        // 'name' => $faker->name,
        "content"=>$faker->sentence(),
        "created_at"=>date("Y-m-d H:i:s",time()),
        "updated_at"=>date("Y-m-d H:i:s",time()),
    ];
});
