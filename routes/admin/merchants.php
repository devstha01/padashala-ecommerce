<?php
//merchant module
Route::group(['namespace' => 'Merchant', 'prefix' => 'merchant'], function () {

    Route::group(['middleware' => 'staff_permission:2.Merchant Master.Add New Merchant'], function () {
        Route::get('register', 'MerchantRegisterController@showMerchantRegisterForm')->name('admin-merchant-register');
        Route::post('register', 'MerchantRegisterController@postMerchantRegisterForm')->name('admin-merchant-register-post');
    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.List'], function () {
        // merchant list
        Route::get('list', 'ListController@listMerchant')->name('merchant-list-admin');

        Route::get('order-list', 'ListController@listOrder')->name('order-list-admin');

    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.Product Approval List'], function () {
        Route::get('product-list', 'ListController@productApprovalList')->name('product-approval-admin');
    });
    Route::group(['middleware' => 'staff_permission:2.Merchant Master.Product Approval List'], function () {
        Route::post('admin-approve-status/{id}', 'ListController@approveProduct')->name('admin-approve-status');

    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.Product List'], function () {
        Route::get('product/standard/list', 'StandardProductController@standardProducts')->name('standard-product-admin');
        Route::get('product/all/list', 'StandardProductController@allProducts')->name('all-product-admin');
        Route::get('product/normal/list', 'StandardProductController@normalProducts')->name('normal-product-admin');
        Route::get('product/inactive/list', 'StandardProductController@inactiveProducts')->name('inactive-product-admin');

    });

    Route::group(['middleware' => 'staff_permission:2.Merchant Master.List'], function () {
        Route::post('standard-product/{id}', 'StandardProductController@standardStatus')->name('admin-change-product-standard');

        Route::get('view-merchant/{id}', 'ListController@merchantProduct')->name('merchant-product-id');


        Route::get('edit-merchant/{id}', 'MerchantRegisterController@editMerchant')->name('edit-merchant-id');
        Route::get('change-status-merchant/{id}', 'MerchantRegisterController@changeStatus')->name('change-status-merchant-admin');

        Route::post('merchant-submit-doc/{id}', 'MerchantRegisterController@merchantDoc')->name('admin-submit-doc');
        Route::post('delete-doc/{id}', 'MerchantRegisterController@deleteDoc')->name('admin-delete-doc');


        //admin edit product
        Route::get('add-product/{id}', 'ListController@addProduct')->name('admin-add-product');
        Route::post('add-product/{id}', 'ListController@addProductPost')->name('admin-add-product-post');

//        Route::get('edit/{id}', 'ListController@edit')->name('admin-edit-product');

        Route::get('edit/{slug}', 'ListController@editProductGeneralTab')->name('admin-edit-product');
        Route::get('i-edit/{slug}', 'ListController@editProductImageTab')->name('image-edit-product-admin');
        Route::get('v-edit/{slug}', 'ListController@editProductVariantTab')->name('variant-edit-product-admin');
        Route::get('s-edit/{slug}', 'ListController@editProductSpecsTab')->name('specs-edit-product-admin');

//        Specifications
        Route::post('s-edit/{id}/add', 'ListController@addSpecs')->name('add-specs-product-admin');
        Route::post('s-edit/{id}/update', 'ListController@updateSpecs')->name('update-specs-product-admin');
        Route::post('s-edit/{id}/delete', 'ListController@deleteSpecs')->name('delete-spec-product-admin');

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


        Route::post('admin-delete/{id}/product', 'ListController@deleteProductBefore')->name('admin-delete-product-status');

        Route::get('featured-product-request/{id}', 'ListController@featuredProductRequest')->name('admin-featured-product-request');

        Route::post('submit-merchant-profile-edit/{id}', 'MerchantRegisterController@submitProfileEdit')->name('submit-merchant-profile-edit');
        Route::post('submit-merchant-image/{id}', 'MerchantRegisterController@uploadImage')->name('admin-merchant-image-edit');
        Route::post('submit-merchant-signature/{id}', 'MerchantRegisterController@uploadSignatureImage')->name('admin-merchant-signature-edit');
        Route::post('submit-merchant-pass/{id}', 'MerchantRegisterController@submitPasswordEdit')->name('submit-merchant-pass');

        Route::get('standard-product/{id}/add', 'ListController@merchantStandardProduct')->name('admin-add-standard-product');
        Route::post('standard-product/{merchant_id}/{id}/add', 'ListController@merchantStandardProductPost')->name('create-product-admin-standard-post');

    });
    Route::group(['middleware' => 'staff_permission:1.Merchant Master.Order list'], function () {
        Route::get('detail/{id}/{m_id}', 'ListController@orderdetails')->name('admin-order-details');
        Route::get('invoice/{id}/{m_id}', 'ListController@orderInvoice')->name('admin-order-invoice');
    });

    Route::group(['middleware' => 'staff_permission:2.Merchant Master.Order list'], function () {

        Route::post('item/status/{id}', 'ListController@itemStatusChange')->name('admin-item-status-change');
        Route::post('item/shipping/{id}', 'ListController@itemShipping')->name('admin-item-shipping');
    });

});
