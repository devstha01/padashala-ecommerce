<?php


//Member and Merchant Requests to Admin  //Feature Product Request
Route::group(['namespace' => 'Request', 'prefix' => 'request'], function () {


//    Route::group(['middleware' => 'staff_permission:1.E-Commerce.Member Cash Withdrawal Request'], function () {
//        Route::get('member-cash-withdraw-request', 'RequestController@memberCashWithdrawRequest')->name('admin-member-cash-withdraw-request');
//    });

    Route::group(['middleware' => 'staff_permission:1.E-Commerce.Merchant Cash Withdrawal Request'], function () {
        Route::get('merchant-cash-withdraw-request', 'RequestController@merchantCashWithdrawRequest')->name('admin-merchant-cash-withdraw-request');
    });

    Route::group(['middleware' => 'staff_permission:1.E-Commerce.Featured Product Request'], function () {
        Route::get('merchant-featured-product-request', 'RequestController@merchantFeaturedProductRequest')->name('admin-merchant-featured-product-request');
    });

//    Route::group(['middleware' => 'staff_permission:2.E-Commerce.Member Cash Withdrawal Request'], function () {
//        Route::get('member-cash-withdraw-accept/{id}', 'RequestController@withdrawAcceptance')->name('admin-member-cash-withdraw-accept');
//    });

    Route::group(['middleware' => 'staff_permission:2.E-Commerce.Merchant Cash Withdrawal Request'], function () {

        Route::get('merchant-cash-withdraw-accept/{id}', 'RequestController@merchantWithdrawAcceptance')->name('admin-merchant-cash-withdraw-accept');
    });

    Route::group(['middleware' => 'staff_permission:2.E-Commerce.Featured Product Request'], function () {

        Route::get('merchant-featured-product-accept/{id}', 'RequestController@featuredproductAcceptance')->name('admin-merchant-featured-product-accept');
        Route::get('merchant-feature-product-delete/{id}', 'RequestController@deleteFeatureProduct')->name('admin-merchant-feature-product-delete');
        Route::get('merchant-feature-product-cancel/{id}', 'RequestController@cancelFeatureProduct')->name('admin-merchant-feature-product-cancel');
    });
});


//Category module
Route::group(['namespace' => 'Ecommerce', 'prefix' => 'category'], function () {

    Route::group(['middleware' => 'staff_permission:1.E-Commerce.Category'], function () {
        Route::get('', 'CategoryController@viewCategory')->name('view-category-e-commerce-admin');
    });

    Route::group(['middleware' => 'staff_permission:2.E-Commerce.Category'], function () {

        Route::post('add-category', 'CategoryController@addCategory')->name('add-category-e-commerce-admin');
        Route::post('add-sub-category', 'CategoryController@addSubCategory')->name('add-sub-category-e-commerce-admin');
        Route::post('edit-category', 'CategoryController@editCategory')->name('edit-category-e-commerce-admin');

        Route::get('delete-category/{id}', 'CategoryController@deleteCategory')->name('delete-category-e-commerce-admin');
        Route::get('delete-sub-category/{id}', 'CategoryController@deleteSubCategory')->name('delete-sub-category-e-commerce-admin');
        Route::get('delete-sub-child-category/{id}', 'CategoryController@deleteSubChildCategory')->name('delete-sub-child-category-e-commerce-admin');

        Route::get('change-status-category/{type}/{id}', 'CategoryController@changeStatus')->name('status-category-admin');

    });

//ajax request response
    Route::get('category', 'CategoryController@getCategory');
    Route::get('sub-category', 'CategoryController@getSubCategory');
    Route::get('sub-child-category', 'CategoryController@getSubChildCategory');

});
