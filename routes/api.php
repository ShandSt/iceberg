<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Public methods is suck.
Route::get('/status', [
    'as' => 'status',
    'uses' => 'StatusController@status'
]);


Route::group(['prefix' => 'categories'], function () {
    Route::get('/', 'CategoryController@index');
    Route::get('/{id}/products', 'CategoryController@getProducts');
});

Route::get('/productsnew', [
    'as' => 'productsnew',
    'uses' => 'ProductsController@productsnew',
]);

Route::group(['middleware' => 'guest'], function () {

    Route::get('/prod', function () {
        return json_encode(\App\Models\Product::all());
    });

    Route::get('/address', function () {
        $cities = \App\Models\City::select('id', 'city')->get();
        $streets = \App\Models\Street::select('id', 'street', 'city_id')->get();
        return compact('cities', 'streets');
    });

    Route::get('/lastorder/{address_id}/{status}', [
        'uses' => 'OrderController@lastOrder',
        'as' => 'order.lastorder',
    ]);

    Route::post('/registration', [
        'uses' => 'RegistrationController@register',
        'as' => 'registration'
    ]);

    Route::post('/login', [
        'uses' => 'LoginController@login',
        'as' => 'login'
    ]);

    Route::post('/user/activate', [
        'uses' => 'UserController@activate',
        'as' => 'activate'
    ]);
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/registration/code', [
        'as' => 'registration.code',
        'uses' => 'RegistrationController@confirm',
    ]);

    Route::group(['middleware' => 'onlyactiveusers'], function () {
        Route::get('/settings', [
            'as' => 'settings.index',
            'uses' => 'SettingsController@index',
        ]);

        Route::post('/changetoken', [
            'uses' => 'UserController@changeDeviceToken',
            'as' => 'change.device.token'
        ]);

        Route::post('/settings', [
            'as' => 'settings.append_option',
            'uses' => 'SettingsController@appendOption',
        ]);

        Route::put('/settings', [
            'as' => 'settings.refresh_options',
            'uses' => 'SettingsController@refreshOptions',
        ]);

        Route::get('/user', [
            'as' => 'user.index',
            'uses' => 'UserController@user',
        ]);

        Route::post('/user', [
            'as' => 'user.update',
            'uses' => 'UserController@update'
        ]);

        Route::post('/user/update_phone', [
            'as' => 'user.update_phone',
            'uses' => 'UserController@updatePhone'
        ]);

        Route::post('/user/device', [
            'as' => 'user.update_device',
            'uses' => 'UserController@updateDevice'
        ]);

        Route::post('/user/consumption', [
            'as' => 'user.consumption',
            'uses' => 'UserController@water',
        ]);

        Route::post('/user/remaining', [
            'as' => 'user.remaining',
            'uses' => 'UserController@remaining',
        ]);

        /**
         * Address
         */
        Route::get('/user/address', [
            'as' => 'user.address',
            'uses' => 'UserController@address',
        ]);

        Route::post('/user/address', [
            'as' => 'user.address.add',
            'uses' => 'UserController@addAddress',
        ]);

        Route::put('/user/address/{id}', [
            'as' => 'user.address.change',
            'uses' => 'UserController@changeAddress'
        ])->where('id', '[0-9]+');

        Route::delete('/user/address/{id}', [
            'as' => 'user.address.delete',
            'uses' => 'UserController@deleteAddress'
        ])->where('id', '[0-9]+');

        Route::get('/user/address/cities', 'AddressesController@getCities');
        Route::get('/user/address/cities/{city}', 'AddressesController@getStreets');
        // end address


        /**
         * Products
         */
        Route::get('/products', [
            'as' => 'products',
            'uses' => 'ProductsController@index',
        ]);

        Route::get('/products/specialOffers', [
            'as' => 'special',
            'uses' => 'ProductsController@special',
        ]);
        // end products


        /**
         * Orders
         */

        Route::post('/order', [
            'as' => 'order.store',
            'uses' => "OrderController@store",
        ]);

        Route::get('/order/{id}', [
            'as' => 'order.show',
            'uses' => 'OrderController@check',
        ])->where('id', '[0-9]+');

        Route::put('/order/{id}', [
            'as' => 'order.update',
            'uses' => 'OrderController@update',
        ])->where('id', '[0-9]+');

        Route::get('/order/{id}/pay', [
            'as' => 'order.pay',
            'uses' => 'OrderController@pay',
        ]);

        Route::put('/order/{id}/cancel', [
            'as' => 'order.cancel',
            'uses' => 'OrderController@cancel',
        ]);

        Route::post('/revise', [
            'as' => 'revise',
            'uses' => 'SendMessageController@revise',
        ]);

        Route::post('/feedback', [
            'as' => 'feedback',
            'uses' => 'SendMessageController@feedback',
        ]);

        // end orders

        /**
         * User orders
         */

        Route::get('/user/orders', [
            'as' => 'user.orders.get',
            'uses' => 'UserController@getOrders',
        ]);

        Route::get('/user/orders/sync', [
            'as' => 'user.orders.sync',
            'uses' => 'UserController@syncOrders',
        ]);

        // end user orders
    });

    /**
     * Categories Routes
     */


});

/**
 * Order billing
 */
Route::get('/https://api.iceberg-aqua.ru/api/order/{id}/pay_confirm/{hash}', 'OrderController@pay_confirm');

Route::get('/order/{id}/pay_confirm/{hash}', [
    'as' => 'order.pay_confirm',
    'uses' => 'OrderController@pay_confirm',
]);

Route::get('/order/{id}/pay_reject/{hash}', [
    'as' => 'order.pay_reject',
    'uses' => 'OrderController@pay_reject',
]);

Route::get('/order/pay_test', function () {
    return view('pages.order.success');
});
// order billing

Route::resource('news', 'NewsController', [
    'except' => ['create', 'edit'],
]);

Route::put('orders/status', 'OrderStatusController');
Route::post('push', 'PushController');
