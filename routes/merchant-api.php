<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Api\Merchant'], function () {
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');
    Route::post('token-check', 'LoginController@tokenCheck');
    Route::post('register', 'LoginController@register');

    Route::get('dashboard', 'ProfileController@dashboard');

    //profile
    Route::post('change-pass', 'ProfileController@changePass');
    Route::post('change-transaction-pass', 'ProfileController@changeTransactionPass');
    Route::post('set-general', 'ProfileController@setGeneral');
    Route::post('set-business', 'ProfileController@setBusiness');

    Route::get('get-bank', 'ProfileController@getBank');
    Route::post('set-bank', 'ProfileController@setBank');
    Route::post('wallet-withdraw', 'ProfileController@walletWithdraw');

//    payemnt request
    Route::get('get-wallet', 'PayRequestController@getWallet');
    Route::post('customer-exist', 'PayRequestController@customerExist');
    Route::post('qr-customer-exist', 'PayRequestController@qrCustomerExist');
    Route::post('pay-request', 'PayRequestController@requestPay');

    Route::get('get-request', 'PayRequestController@requestList');
    Route::post('cancel-request', 'PayRequestController@cancelRequest');

//    cash delivery admin bonus
    Route::get('cash-delivery/admin-bonus', 'PayRequestController@cashDeliveryAdminBonus');
    Route::post('cash-delivery/admin-bonus/confirm', 'PayRequestController@cashDeliveryAdminBonusConfirm');


//    wallet transfer
    Route::post('user-exist', 'TransferController@userExist');
    Route::post('qr-user-exist', 'TransferController@qrUserExist');
    Route::post('wallet-transfer', 'TransferController@generateTransfer');

    Route::post('merchant-exist', 'TransferController@merchantExist');
    Route::post('qr-merchant-exist', 'TransferController@qrMerchantExist');
    Route::post('wallet-transfer-merchant', 'TransferController@generateTransferMerchant');



//    category response
    Route::get('category', 'ProductController@getCategory');
    Route::get('sub-category', 'ProductController@getSubCategory');
    Route::get('sub-child-category', 'ProductController@getSubChildCategory');

//    product
    Route::get('view-products', 'ProductController@viewProduct');
    Route::get('detail-product/{id}', 'ProductController@detailProduct');

    Route::post('add-product', 'ProductController@addProduct');
    Route::post('edit-product/{id?}', 'ProductController@editProduct');
    Route::post('delete-product/{id}', 'ProductController@deleteProduct');

    Route::post('add-product-images/{id}', 'ProductController@addProductImages');
    Route::post('delete-product-images/{id}', 'ProductController@deleteProductImage');

    Route::post('add-variant/{id}', 'ProductController@addProductVariant');
    Route::post('edit-variant/{id}', 'ProductController@editProductVariant');
    Route::post('delete-variant/{id}', 'ProductController@deleteProductVariant');

    Route::post('feature-product-request/{id}', 'ProductController@featureProductRequest');

    Route::post('ch-product/{id}', 'ProductController@editCHProductPost');
    Route::post('ch-variant/{id}', 'ProductController@editCHProductVariant');
    Route::post('trch-product/{id}', 'ProductController@editTRCHProductPost');
    Route::post('trch-variant/{id}', 'ProductController@editTRCHProductPost');


//    order
    Route::get('order-list/{state}', 'OrderController@OrderList')->where('state', 'all|pending|complete');
    Route::get('order-detail', 'OrderController@orderDetail');
    Route::post('order-item-status', 'OrderController@orderItemStatus');

//ios product
    Route::post('mp-add-product', 'ProductController@addProductMP');
    Route::post('mp-edit-product', 'ProductController@editProductMP');
    Route::post('mp-add-product-images', 'ProductController@addProductImagesMP');

});


