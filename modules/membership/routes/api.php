<?php

Route::group(['prefix' => 'membership'], function () {
    Route::post('/register', 'MembershipApiController@store')->name('api.membership.register');
    Route::post('/login', 'MembershipApiController@login')->name('api.membership.login');
    Route::get('/verify/{member}', 'MembershipApiController@verify')->name('api.membership.verify');
    Route::post('/reset', 'MembershipApiController@reset')->name('api.membership.reset');
    Route::post('/resend-verify', 'MembershipApiController@resendVerify')->name('api.membership.resend-verify');
    Route::get('/show/{member}', 'MembershipApiController@show')->name('api.membership.show');
});

Route::group(['prefix' => 'membership', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'MembershipApiController@index');
    Route::get('/preview-identity/{identity}', 'MembershipApiController@previewIdentity')->name('api.membership.preview-identity');
    Route::post('/update', 'MembershipApiController@update')->name('api.membership.update');
    Route::get('/logout', 'MembershipApiController@logout')->name('api.membership.logout');
});
