<?php

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

Route::group(['namespace' => 'Api', 'middleware' => 'api'], function () {
    Route::post('login', 'AuthController@login');

    Route::post('customer/register', 'AuthController@customerRegister');

    Route::post('verify-email', 'AuthController@verifyEmail');

    Route::post('pass-recovery', 'AuthController@passRecovery');

    Route::get('logout', 'AuthController@logout');
    Route::post('logout', 'AuthController@logout');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('token-refresh', 'AuthController@refresh');
        Route::get('user', 'AuthController@getAuthenticatedUser');
        Route::post('member/add-new-member', 'MemberController@postMemberRegister');
        Route::get('member/isAvailable', 'MemberController@checkMemberExistence');
        Route::get('member/getPosition', 'MemberController@getPosition');
        Route::get('member/mobile/get-position', 'MemberController@getPositionToMobile');
        Route::get('member/mobile-ios/get-position', 'MemberController@getIosPositionToMobile');
        Route::get('member/getStandardTree', 'MemberController@getStandardTree');
        Route::get('member/getAutoTree', 'MemberController@getAutoTree');
        Route::get('member/getSpecialTree', 'MemberController@getSpecialTree');
    });

    //Package List
    Route::get('get-packages', 'MemberController@getPackages');


    //mobile api
    Route::get('get-home', 'ApiController@home');

    Route::get('products', 'ApiController@Products');
    Route::get('featured-products', 'ApiController@FeaturedProducts');
    Route::get('flash-products', 'ApiController@FlashProducts');
    Route::get('categories', 'ApiController@allCategories');

    Route::get('only-categories', 'ApiController@onlyCategories');
    Route::get('category-product/{slug?}', 'ApiController@categoryProduct');
    Route::get('sub-category-product/{slug?}', 'ApiController@subCategoryProduct');
    Route::get('sub-child-category-product/{slug?}', 'ApiController@subChildCategoryProduct');

    Route::get('product-detail', 'ApiController@productDetail');

    Route::get('search-product', 'ApiController@searchProduct');

    Route::get('get-country', 'ApiController@getCountry');


    //cart
    Route::get('cart-list', 'CartController@cartList');

    Route::get('get-checkout', 'CartController@getCheckout');
    Route::post('post-checkout', 'CartController@postCheckout');
    Route::post('post-checkout-customer', 'CartController@postCheckoutCustomer');
//    Route::post('cancel-checkout', 'CartController@cancelCheckout');

    Route::post('add-cart-item', 'CartController@addCartItem');
    Route::post('quantity-cart-item', 'CartController@quantityCartItem');

    Route::post('remove-cart-item', 'CartController@removeCartItem');
    Route::post('clear-cart', 'CartController@clearCart');


    //order
    Route::get('order-list', 'OrderController@orderList');
    Route::post('order-detail', 'OrderController@orderDetail');

//    order changes
    Route::get('order-list-invoice', 'OrderController@orderListInvoice');
    Route::post('order-detail-invoice', 'OrderController@orderDetailInvoice');


    //profile
    Route::get('get-user', 'ProfileController@getUser');
    Route::get('get-user-country', 'ProfileController@getUserCountry');

    Route::post('change-pass', 'ProfileController@changePass');
    Route::post('change-transaction-pass', 'ProfileController@changeTransactionPass');
    Route::post('set-mobile', 'ProfileController@setMobile');
    Route::post('set-email', 'ProfileController@setEmail');
    Route::post('set-address', 'ProfileController@setAddress');
    Route::post('set-general', 'ProfileController@setGeneral');
    Route::post('set-bank', 'ProfileController@setBank');

    //member
    Route::get('member-by-id', 'MemberController@memberById');

    Route::get('member-detail', 'MemberController@memberDetail');
    Route::get('wallet-detail', 'MemberController@walletDetail');
    Route::get('bank-detail', 'MemberController@bankDetail');

    Route::get('get-wallet-list', 'MemberController@walletList');

    Route::post('convert-wallet', 'MemberController@convertWallet');
    Route::post('user-exist', 'MemberController@userExist');
    Route::post('qr-user-exist', 'MemberController@qrUserExist');

    Route::post('wallet-transfer', 'MemberController@generateTransfer');

    Route::get('wallet-request-list', 'MemberController@walletRequestList');
    Route::post('wallet-request', 'MemberController@requestTransfer');
    Route::post('wallet-request-approve', 'MemberController@requestTransferApprove');
    Route::post('wallet-request-decline', 'MemberController@requestTransferDecline');
//    Route::post('generate-transfer', 'MemberController@generateTransfer');
//    Route::post('confirm-transfer', 'MemberController@confirmTransfer');
    Route::post('wallet-withdraw', 'MemberController@walletWithdraw');

    //dashboard
    Route::get('member-list', 'DashboardController@memberList');

    Route::get('test', 'CartController@test');

//payment
    Route::get('request-list', 'PaymentController@requestList');
    Route::post('accept-payment', 'PaymentController@acceptPayment');
    Route::post('decline-payment', 'PaymentController@declinePayment');

    Route::post('merchant-exist', 'PaymentController@merchantExist');
    Route::post('qr-merchant-exist', 'PaymentController@qrMerchantExist');
    Route::post('pay-merchant', 'PaymentController@generatePay');
//    Route::post('confirm-pay', 'PaymentController@confirmPay');

//Customer Transfer
    Route::post('customer-exist', 'TransferController@customerExist');
    Route::post('qr-customer-exist', 'TransferController@qrCustomerExist');
    Route::post('transfer-customer', 'TransferController@transferCustomer');


    Route::get('topuptest', function () {
//        echo "<pre>";
//
//        print_r(\App\Models\User::all()->pluck('user_name'));
//
//        echo "</pre>";

//        \App\Models\Wallet::where('name', 'ecash_wallet')->first()->update(['detail' => 'Cash Wallet']);
//        \App\Models\Wallet::where('name', 'evoucher_wallet')->first()->update(['detail' => 'Voucher Wallet']);
//        \App\Models\Wallet::where('name', 'r_point')->first()->update(['detail' => 'R Wallet']);
//        \App\Models\User::where('user_name', 'testing123')->first()->update(['status'=>0]);
//        \App\Models\UserPayment::create([
//            'to_merchant_id' => 1,
//            'from_member_id' => 1,
//            'amount' => 123,
//            'wallet_id' => 1,
//            'remarks' => 'Test']);

//        dd(\App\Models\User::where('is_member', 0)->pluck('id','user_name')->toArray());
        $data = \App\Models\Members\MemberAsset::find(1);
        $data->update(['ecash_wallet' => 10000, 'evoucher_wallet' => 10000]);
        $data = \App\Models\Members\MemberAsset::find(1)->ecash_wallet;
        return response()->json($data);
    });
});
