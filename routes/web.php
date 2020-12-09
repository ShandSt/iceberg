<?php

Route::get('/', 'Web\CatalogController@index')->name('catalog');
Route::get('/product/{product}', 'Web\CatalogController@show');

Route::get('/want', 'Web\WantController@index')->name('want');
Route::post('/want', 'Web\WantController@store')->name('want.order');

Route::get('/search', 'Web\SearchController')->name('search');

Route::post('cart', 'Web\CartController@store');

Route::get('checkout', 'Web\CheckoutController@cart')->name('cart');
Route::post('checkout/confirm', 'Web\CheckoutController@confirmation')->name('order-confirmation');
Route::get('checkout/delivery/{hash}', 'Web\CheckoutController@deliveryTime')->name('order-delivery');
Route::post('checkout', 'Web\CheckoutController@checkout')->name('checkout');
Route::get('order', 'Web\CheckoutController@order')->name('order');
Route::get('pay/{orderId}', 'Web\CheckoutController@pay')->name('pay');
Route::get('pay/confirm/{hash}', 'Web\CheckoutController@pay_confirm')->name('pay-confirm');
Route::get('pay/reject/{hash}', 'Web\CheckoutController@pay_reject')->name('pay-reject');

Route::get('/price', 'Web\PriceController')->name('price');

Route::view('delivery', 'pages.delivery')->name('page.delivery');
Route::view('contacts', 'pages.contacts')->name('page.contacts');
Route::view('policy', 'pages.policy')->name('page.policy');

Route::get('sendpushservice', 'Web\PushController@create');
Route::post('sendpushservice', 'Web\PushController@store');
