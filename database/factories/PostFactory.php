<?php

$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'title'   => $faker->sentence,
        'body'    => $faker->paragraph,
        'user_id' => factory(App\User::class)->create()->id,
    ];
});
