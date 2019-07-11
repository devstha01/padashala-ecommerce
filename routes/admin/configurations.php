<?php

Route::group(['middleware' => 'role_is_admin'], function () {

//    //Shopping / Commisions
//    Route::group(['namespace' => 'Shopping', 'prefix' => 'shopping'], function () {
//
//        Route::get('list', 'ShoppingController@listView')->name('admin-shopping-list');
//
//        Route::post('edit-shopping', 'ShoppingController@editShopping');
//        Route::post('edit-merchant-rate', 'ShoppingController@editMerchantRate')->name('edit-merchant-rate');
//
//        Route::post('update-single-shopping-rate', 'ShoppingController@updateSingleShoppingRate');
//
//        Route::get('add-shopping-rate', 'ShoppingController@addShoppingRate')->name('add-shopping-rate');
//        Route::post('create-shopping-rate', 'ShoppingController@createShoppingRate')->name('create-shopping-rate');
//        Route::get('edit-shopping-rate', 'ShoppingController@editShoppingRate')->name('edit-shopping-rate');
//        Route::post('edit-shopping-rate', 'ShoppingController@submitShoppingRate')->name('post-shopping-rate');
//        Route::get('delete-shopping-rate', 'ShoppingController@deleteShoppingRate')->name('delete-shopping-rate');
//
//    });
    Route::group(['namespace' => 'Configuration', 'prefix' => 'config'], function () {

//        Route::get('refaral-bonus-lists', 'ConfigController@bonusList')->name('refaral-bonus-list');
//        Route::get('packages', 'ConfigController@getPackage')->name('packages');
//
//        Route::get('holiday-dates', 'ConfigController@holidayDates')->name('holiday-dates');

        Route::get('minmax/withdraw-config', 'MinMaxController@withdrawConfig')->name('admin-withdraw-config');
        Route::get('minmax/cash-config', 'MinMaxController@cashConfig')->name('admin-cash-config');
//        Route::get('minmax/voucher-config', 'MinMaxController@voucherConfig')->name('admin-voucher-config');
//        Route::get('minmax/rpoint-config', 'MinMaxController@rpointConfig')->name('admin-rpoint-config');
//        Route::get('minmax/chip-config', 'MinMaxController@chipConfig')->name('admin-chip-config');

//        Route::post('update-single-referral', 'ConfigController@updateSingleReferral');

//        Route::get('add-referral', 'ConfigController@addBonus')->name('add-refaral-bonus');
//        Route::post('add-referral', 'ConfigController@storeBonus')->name('add-refaral-bonus');
//        Route::get('edit-referal/{id}', 'ConfigController@editBonus')->name('edit-refaral-bonus');
//        Route::post('edit-referal', 'ConfigController@updateBonus')->name('edit-refaral-bonus');
//        Route::get('delete-referal/{id}', 'ConfigController@deleteBonus');
//
//        Route::get('add-holiday', 'ConfigController@addHoliday')->name('add-holiday');
//        Route::post('add-holiday', 'ConfigController@storeHoliday')->name('add-holiday');
//        Route::get('edit-holiday/{id}', 'ConfigController@editHoliday')->name('edit-holiday');
//        Route::post('edit-holiday', 'ConfigController@updateHoliday')->name('edit-holiday');
//        Route::get('delete-holiday/{id}', 'ConfigController@deleteHoliday');

//            add holiday calendar
//        Route::get('add-calendar-holiday', 'ConfigController@addCalendarEvent');
//        Route::get('remove-calendar-holiday', 'ConfigController@removeCalendarEvent');
//
//        Route::get('check-calendar-holiday', 'ConfigController@checkCalendarEvent');
//
//        Route::get('edit-package/{id}', 'ConfigController@editPackage')->name('edit-package');
//        Route::post('edit-package', 'ConfigController@updatePackage')->name('edit-package');


        Route::post('withdraw-config', 'MinMaxController@withdrawConfigPost')->name('admin-withdraw-config-post');
    });
});