<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Gift\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'gifts', 'as' => 'gift.'], function () {
            Route::resource('', 'GiftController')->parameters(['' => 'gift']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'GiftController@deletes',
                'permission' => 'gift.destroy',
            ]);
        });
    });

});
