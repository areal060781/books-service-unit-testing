<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Book;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(Book::class, function (Faker $faker) {
    $title = $faker->sentence(rand(3, 10));
    return [
        'title' => substr($title, 0, strlen($title) - 1),
        'description' => $faker->text,
        'author' => $faker->name
    ];
});
