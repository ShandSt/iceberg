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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'phone' => random_int(10000000, 99999999),
        'guid' => $faker->uuid,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'type' => 'individual',
        'company_name' => 'Company LLC',
        'inn' => str_random(6),
        'status' => 'active',
        'api_token' => str_random(60),
    ];
});


$factory->define(\App\Models\ConfirmCode::class, function (Faker\Generator $faker) {
    return [
        'code' => random_int(1000, 9999),
        'user_id' => 1
    ];
});

$factory->define(\App\Models\Product::class, function (\Faker\Generator $faker) {
    return [
        'guid' => $faker->uuid,
        'preview_picture' => $faker->imageUrl(100, 100, 'cats', false),
        'detail_picture' => $faker->imageUrl(640, 480, 'cats', false),
        'name' => $faker->userName,
        'description' => $faker->text(100),
        'price' => $faker->numberBetween(100, 200),
    ];
});

$factory->define(\App\Models\City::class, function (\Faker\Generator $faker) {
    return [
        'city' => $faker->city,
    ];
});

$factory->define(\App\Models\Address::class, function (\Faker\Generator $faker) {
    return [
        'guid' => $faker->uuid,
        'street' => $faker->streetName,
        'entrance' => $faker->numberBetween(1, 30),
        'floor' => $faker->numberBetween(1, 40),
        'apartment' => $faker->numberBetween(1, 400),
        'house' => "".$faker->randomNumber(),
        'city_id' => function () { return factory(\App\Models\City::class)->create()->id; },
    ];
});

$factory->define(\App\Models\Order::class, function (\Faker\Generator $faker) {
    return [
        'guid' => $faker->uuid,
        'date_of_delivery' => \Carbon\Carbon::now()->addDay()->getTimestamp(),
        'date_of_delivery_variants' => [
            [
                'name' => 'today',
                'date' => \Carbon\Carbon::now()->getTimestamp(),
            ],
            [
                'name' => 'today',
                'date' => \Carbon\Carbon::now()->getTimestamp(),
            ],
        ],
        'payment_method' => 'Card',
        'payment_status' => 'new',
        'price' => $faker->numberBetween(200, 500),
        'bottles' => $faker->numberBetween(1, 20),
    ];
});

$factory->define(\App\Models\News::class, function (\Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'text' => $faker->text(),
        'type' => 'main',
        'image' => $faker->imageUrl(200, 200 , 'cats', false),
    ];
});