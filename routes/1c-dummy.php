<?php

use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return new \Illuminate\Http\Response('', 200);
});

Route::get('/ping', function () {
    return (new \Illuminate\Http\Response())->setStatusCode(200);
});

Route::post('/user', function () {
    return (new \Illuminate\Http\Response())->setStatusCode(200);
});

Route::get('/user/findByPhone', function (\Illuminate\Http\Request $request) {
    return [
        'id' => 0,
        'guid' => str_random(20),
        'phone' => $request->get('phone'),
        'first_name' => 'Dummy',
        'last_name' => 'Dummy',
        'has_debt' => false,
        'type' => 'individual',
        'company_name' => 'string',
        'inn' => 'string',
        'status' => 'active',
    ];
});

Route::get('/user/{id}', function ($id) {
    return [
        'id' => 0,
        'guid' => $id,
        'phone' => '+380675906752',
        'first_name' => 'Dummy',
        'last_name' => 'Dummy',
        'balance' => random_int(100, 500),
        'has_debt' => false,
        'type' => 'individual',
        'company_name' => 'string',
        'inn' => 'string',
        'status' => 'active',
    ];
});

Route::put('/user/{id}', function () {
    return (new \Illuminate\Http\Response())->setStatusCode(200);
});

Route::get('/bottles/{user_id}', function ($user_id) {
    return [
        'user' => [
            'id' => 0,
            'guid' => $user_id,
            'phone' => '+380675906752',
            'first_name' => 'Dummy',
            'last_name' => 'Dummy',
            'has_debt' => false,
            'type' => 'individual',
            'company_name' => 'string',
            'inn' => 'string',
            'status' => 'active',
        ],
        'count' => 0,
        'speed' => 0,
        'guess' => 0,
    ];
});

Route::post('/bottles/{user_id}', function ($user_id) {
    return (new \Illuminate\Http\Response())->setStatusCode(200);
});

Route::get('/products', function (\Illuminate\Http\Request $request) {

    $faker = Faker\Factory::create();

    $data = [
        [
            'guid' => $faker->uuid,
            'preview_picture' => $faker->imageUrl(100, 100, 'cats', false),
            'detail_picture' => $faker->imageUrl(640, 480, 'cats', false),
            'name' => $faker->userName,
            'description' => $faker->text(100),
            'price' => $faker->numberBetween(100, 200),
        ],
        [
            'guid' => $faker->uuid,
            'preview_picture' => $faker->imageUrl(100, 100, 'cats', false),
            'detail_picture' => $faker->imageUrl(640, 480, 'cats', false),
            'name' => $faker->userName,
            'description' => $faker->text(100),
            'price' => $faker->numberBetween(100, 200),
        ],
        [
            'guid' => $faker->uuid,
            'preview_picture' => $faker->imageUrl(100, 100, 'cats', false),
            'detail_picture' => $faker->imageUrl(640, 480, 'cats', false),
            'name' => $faker->userName,
            'description' => $faker->text(100),
            'price' => $faker->numberBetween(100, 200),
        ],
    ];

    return new \Illuminate\Http\JsonResponse($data, 200, [
        'Pagination-Count' => '100',
        'Pagination-Limit' => $request->get('limit'),
        'Pagination-Page' => $request->get('page'),
    ]);
});

Route::get('/products/{guid}', function ($guid) {
    $faker = Faker\Factory::create();

    return [
        'guid' => $guid,
        'preview_picture' => $faker->imageUrl(100, 100, 'cats', false),
        'detail_picture' => $faker->imageUrl(640, 480, 'cats', false),
        'name' => $faker->userName,
        'description' => $faker->text(100),
        'price' => $faker->numberBetween(100, 200),
        'related_products' => [
            $faker->uuid,
            $faker->uuid,
            $faker->uuid,
        ],
    ];
});

/**
 * Address
 */


Route::get('/addresses/findByUser', function (\Illuminate\Http\Request $request) {
    $faker = \Faker\Factory::create();

    $data = [];

    for ($i = 0; $i < 5; $i++) {
        $data[] = [
            'id' => $faker->numberBetween(100, 200),
            'guid' => $faker->uuid,
            'name' => $faker->streetName,
            'entrance' => $faker->numberBetween(1, 30),
            'floor' => $faker->numberBetween(1, 40),
            'apartment' => $faker->numberBetween(1, 400),
        ];
    }

    return $data;
});

Route::get('/addresses/{guid}', function () {
    $faker = \Faker\Factory::create();

    return [
        'id' => $faker->numberBetween(100, 200),
        'guid' => $faker->uuid,
        'name' => $faker->streetName,
        'entrance' => $faker->numberBetween(1, 30),
        'floor' => $faker->numberBetween(1, 40),
        'apartment' => $faker->numberBetween(1, 400),
    ];
});




Route::post('/addresses', function () {
    $faker = \Faker\Factory::create();

    return [
        'guid' => $faker->uuid,
    ];
});

Route::put('/addresses/{guid}', function () {
    return (new \Illuminate\Http\Response())->setStatusCode(200);
});

Route::delete('/addresses/{guid}', function () {
    return (new \Illuminate\Http\Response())->setStatusCode(200);
});


/**
 * Orders
 */

Route::post('/orders',function () {
   return (new \Illuminate\Http\Response())->setStatusCode(200);
});

/**
 * consumption
 */

Route::post('/user/{id}/consumption', function ($id) {
   return [];
});

Route::get('/user/{id}/consumption', function ($id) {
    return [
        'consumption' => 200.00
    ];
});