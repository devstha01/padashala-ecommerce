<?php
//merchant module
Route::group(['namespace' => 'Merchant', 'prefix' => 'merchant'], function () {

    Route::group(['middleware' => 'staff_permission:1.Merchant Master.Add New Merchant'], function () {
        Route::get('register', 'MerchantRegisterController@showMerchantRegisterForm')->name('admin-merchant-register');
    });
    Route::group(['middleware' => 'staff_permission:2.Merchant Master.Add New Merchant'], function () {
        Route::post('register', 'MerchantRegisterController@postMerchantRegisterForm')->name('admin-merchant-register-post');
    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.List'], function () {
        // merchant list
        Route::get('list', 'ListController@listMerchant')->name('merchant-list-admin');

        Route::get('order-list', 'ListController@listOrder')->name('order-list-admin');

        Route::get('product-list', 'ListController@productApprovalList')->name('product-approval-admin');
    });
    Route::group(['middleware' => 'staff_permission:2.Merchant Master.List'], function () {

    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.Profile'], function () {
        Route::get('view-merchant/{id}', 'ListController@merchantProduct')->name('merchant-product-id');
        Route::get('detail/{id}/{m_id}', 'ListController@orderdetails')->name('admin-order-details');

        Route::get('invoice/{id}/{m_id}', 'ListController@orderInvoice')->name('admin-order-invoice');

    });
    Route::group(['middleware' => 'staff_permission:2.Merchant Master.Profile'], function () {

//        Route::get('grant-wallet/{id}', 'MerchantController@showGrantWallet')->name('admin-merchant-grant');
//        Route::post('grant-wallet', 'MerchantController@postGrantWallet')->name('admin-merchant-grant-post');
//        Route::get('retain-wallet/{id}', 'MerchantController@showRetainWallet')->name('admin-merchant-retain');
//        Route::post('retain-wallet', 'MerchantController@postRetainWallet')->name('admin-merchant-retain-post');

        Route::get('edit-merchant/{id}', 'MerchantRegisterController@editMerchant')->name('edit-merchant-id');
        Route::get('change-status-merchant/{id}', 'MerchantRegisterController@changeStatus')->name('change-status-merchant-admin');


        //admin edit product
        Route::get('add-product/{id}', 'ListController@addProduct')->name('admin-add-product');
        Route::post('add-product/{id}', 'ListController@addProductPost')->name('admin-add-product-post');

//        Route::get('edit/{id}', 'ListController@edit')->name('admin-edit-product');

        Route::get('edit/{slug}', 'ListController@editProductGeneralTab')->name('admin-edit-product');
        Route::get('i-edit/{slug}', 'ListController@editProductImageTab')->name('image-edit-product-admin');
        Route::get('v-edit/{slug}', 'ListController@editProductVariantTab')->name('variant-edit-product-admin');

        Route::post('edit/{id}', 'ListController@editProductPost')->name('admin-edit-product-post');
        Route::post('add-product-images/{id}', 'ListController@addProductImages')->name('admin-add-product-images');
        Route::post('add-product-variant/{id}', 'ListController@addProductVariant')->name('admin-add-product-variant');

        Route::post('update-product-variant/image', 'ListController@updateProductVariantImage')->name('admin-update-product-variant-image');
        Route::post('update-product-variant', 'ListController@updateProductVariant');

        Route::post('edit-product-variant', 'ListController@editProductVariant')->name('admin-edit-product-variant-merchant');
        Route::get('delete-product-variant/{id}', 'ListController@deleteProductVariant')->name('admin-delete-product-variant');
        Route::post('delete-product-variant/{id}', 'ListController@deleteProductVariant')->name('admin-delete-product-variant-post');
        Route::get('delete-product-image/{id}', 'ListController@deleteProductImage')->name('admin-delete-product-image-merchant');

        Route::post('delete-product/{id}', 'ListController@deleteProduct')->name('admin-change-product-status');


        Route::post('admin-approve-status/{id}', 'ListController@approveProduct')->name('admin-approve-status');
        Route::post('admin-delete/{id}/product', 'ListController@deleteProductBefore')->name('admin-delete-product-status');


        Route::post('item/status/{id}', 'ListController@itemStatusChange')->name('admin-item-status-change');
        Route::post('item/shipping/{id}', 'ListController@itemShipping')->name('admin-item-shipping');

        Route::get('featured-product-request/{id}', 'ListController@featuredProductRequest')->name('admin-featured-product-request');


        Route::post('submit-merchant-profile-edit/{id}', 'MerchantRegisterController@submitProfileEdit')->name('submit-merchant-profile-edit');
        Route::post('submit-merchant-image/{id}', 'MerchantRegisterController@uploadImage')->name('submit-merchant-image-edit');

    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.Password'], function () {

    });
    Route::group(['middleware' => 'staff_permission:2.Merchant Master.Password'], function () {
        Route::post('submit-merchant-pass/{id}', 'MerchantRegisterController@submitPasswordEdit')->name('submit-merchant-pass');
//        Route::post('submit-merchant-transaction-pass/{id}', 'MerchantRegisterController@submitTrannsactionPasswordEdit')->name('submit-merchant-transaction-pass');
    });

//    Route::get('get-search-merchant', 'MerchantRegisterController@searchMerchant');
});
