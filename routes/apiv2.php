<?php

Route::post('/users/verify-mobile', 'UserApi@verifyUser');
Route::get('/setting/app-version', 'SettingApi@appVersionShow');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/users/products/comments', 'CommentApi@getUserComments');
    Route::post('/ads/{ads}/buy/wallet', 'AdsApi@payWithWallet')->name('buy_ads.via_wallet');
    Route::post('/ads/{ads}/make-stairs' , 'AdsApi@makeStairs');

});