<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ChaseH\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'handle' => $faker->firstName,
    ];
});

$factory->define(ChaseH\Models\Sharing\Link::class, function (Faker\Generator $faker) {
    $title = $faker->sentence();

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'body' => $faker->paragraph(5),
        'link' => $faker->url,
        'posted_by' => $faker->numberBetween(11, 111)
    ];
});

$factory->define(ChaseH\Models\Sharing\Comment::class, function(Faker\Generator $faker) {
   return [
       'user_id' => $faker->numberBetween(11, 111),
       'parent_id' => $faker->numberBetween(0, 100),
       'body' => $faker->paragraph(),
   ];
});
