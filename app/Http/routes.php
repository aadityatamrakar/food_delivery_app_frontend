<?php

Route::get('/', "HomeController@index")->name('home');

Route::get('/login', "LoginController@index")->name("login");
Route::post('/login', "LoginController@login");
Route::get('/logout', "LoginController@logout")->name("logout");
Route::post('/register', "LoginController@postRegister")->name('register');
Route::post('/check/mobile', "LoginController@checkMobile")->name('checkmobile');
Route::post('/login/otp/request', "LoginController@postOtp")->name('login.get_otp');
//Route::post('/login/otp/check', "LoginController@check_otp")->name('login.check_otp');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/profile', "CustomerController@index")->name("profile");
    Route::post('/profile', "CustomerController@postUpdate");

    Route::get('/wallet', "WalletController@index")->name("wallet");
    Route::get('/wallet/coin/{number}', "WalletController@getCoin")->name("wallet.coin");

    Route::get('/area/get', "HomeController@getArea")->name("area.get");

    Route::get('/restaurant/get', "HomeController@ajaxRestaurant")->name("restaurant.get");
    Route::get('/restaurant/index', function (){ return redirect()->route('home'); });
    Route::post('/restaurant/index', "HomeController@getRestaurant")->name("restaurant.index");
    Route::get('/restaurant/{type}/{id}/{name}', "HomeController@viewRestaurant")->name("restaurant.view");
    Route::post('/cart/checkout', "CartController@checkout_first")->name("cart.checkout");
    Route::post('/cart/confirm', "CartController@checkout_confirm")->name("cart.confirm");
    Route::get('/order/{id}', "HomeController@viewOrder")->name("order.view");
    Route::get('/cart/request/otp', "CartController@checkout_otp")->name("cart.requestotp");

    Route::post('/cart/check', "CartController@cart_check")->name("cart.check");
    Route::post('/coupon/check', "HomeController@coupon_check")->name("coupon.check");
});

Route::group(['middleware' => 'api', 'prefix' => 'api/v2'], function () {
    Route::get('/check', 'ApiController@check');
    Route::get('/send_link/sms/{mobile}', "ApiController@send_link_sms")->name('send_link_sms');
    Route::get('/send_link/email/{email}', "ApiController@send_link_email")->name('send_link_email');
    Route::post('/otp/request', "ApiController@api_postOtp");
    Route::post('/register', 'ApiController@register');
    Route::post('/login', 'ApiController@login');
    Route::post('/profile/update', 'ApiController@updateProfile');
    Route::post('/pin_update', 'ApiController@updatePin');
    Route::post('/check_coupon', 'ApiController@check_coupon');
    Route::post('/place_order', 'ApiController@place_order');
    Route::get('/order_status/{id}', 'ApiController@order_status');
    Route::post('/orders/all', 'ApiController@all_orders');
    Route::post('/wallet/summary', 'ApiController@wallet_summary');
    Route::post('/wallet/balance', 'ApiController@wallet_balance');

    Route::post('/regen/pin', 'ApiController@regen_pin');
    Route::post('/regen/check', 'ApiController@check_regen_pin');

    Route::get('/get_city', 'ApiController@get_city');
    Route::get('/get_area/{city}', 'ApiController@get_area');
    Route::get('/restaurant/{id}/get_menu', 'ApiController@get_menu');
    Route::get('/restaurant/{area_id}/{type}', 'ApiController@get_restaurant');

    Route::get('/test', 'ApiController@test');
    Route::get('/test_call/{m}', 'ApiController@test_call');
    Route::post('/callback_url', 'ApiController@callback_url');
    Route::get('/banner/get', 'ApiController@banner_get');
    Route::post('/confirm_sms', 'ApiController@confirm_sms');
    Route::get('/order_confirmation/{hash}', 'ApiController@get_restaurant_confirm_link')->name('order_confirm_link');
    Route::post('/order_confirmation/{hash}', 'ApiController@restaurant_confirm_link');


    Route::post('/referral/customer/save', 'ApiController@referral_post')->name('referral_save');
    Route::get('/referral/customer', 'ApiController@referral_page');
    Route::post('/referral/customer/otp', 'ApiController@get_referral_otp')->name('referral_otp');

    Route::group(['prefix' => 'railway'], function () {
        Route::get('/pnr/{pnr}', 'ApiController@railway_pnr');
        Route::get('/trains/{q}', 'ApiController@railway_trains');
        Route::get('/stations/{q}', 'ApiController@railway_stations');
        Route::get('/live/{q}/{date}', 'ApiController@railway_live');

        Route::get('/restaurant/{city}', 'ApiController@get_restaurant_train');
    });
});

Route::get('/help_and_support', 'WebsiteController@support')->name('helpsupport');
Route::get('/privacy_policy', 'WebsiteController@privacypolicy')->name('privacypolicy');
Route::get('/refunds_cancellations', 'WebsiteController@refundcancel')->name('refundcancel');
Route::get('/terms_conditions', 'WebsiteController@terms')->name('termsconditions');
Route::get('/about_us', 'WebsiteController@about')->name('about');
Route::get('/contact_us', 'WebsiteController@contact')->name('contact');
Route::get('/careers', 'WebsiteController@careers')->name('careers');