<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('change-member-pass', function () {
    \App\Models\User::where('user_name', 'member')->first()->update([
        'password' => bcrypt('password')
    ]);
    echo 'done';
});

//Localization
Route::get('lang', 'CommonController@chooseLanguage');


// Admin Authentication
Route::get('/admin/login', 'Auth\AdminLoginController@showAdminLoginForm');
Route::post('/admin/login', 'Auth\AdminLoginController@adminLogin');
Route::get('/admin/logout', 'Auth\AdminLoginController@adminLogout');

// Member Authentication
//Route::get('/member/login', 'Auth\LoginController@showMemberLoginForm');
//Route::post('/member/login', 'Auth\LoginController@memberLogin');
Route::get('/member/logout', 'Auth\LoginController@memberLogout');

//Merchant Authentication
Route::get('/merchant/login', 'Auth\MerchantLoginController@showMerchantLoginForm');
Route::post('/merchant/login', 'Auth\MerchantLoginController@merchantLogin');
Route::get('/merchant/logout', 'Auth\MerchantLoginController@merchantLogout');


//Users Frontend
// Profile Controller
Route::get('edit-profile/{id}', 'Auth\ProfileController@editProfile')->name('edit-profile');
Route::post('update-profile', 'Auth\ProfileController@updateProfile')->name('update-profile');
Route::get('view-profile', 'Auth\ProfileController@viewProfile')->name('view-profile');

//Change Password
Route::get('change/password', 'Auth\ProfileController@changePasswordForm');
Route::post('change-password', 'Auth\ProfileController@changePasswordUser')->name('change-password-user');
//Route::post('change-password-trans', 'Auth\ProfileController@changeTransactionPassword')->name('change-password-trans');

//add & edit product category responses
Route::get('merchant/product/get-sub-category', 'backend\ResponseController@getSubCategory');
Route::get('merchant/product/get-sub-child-category', 'backend\ResponseController@getSubChildCategory');
Route::get('minmax-check', 'backend\ResponseController@minmaxCheck');
Route::get('colors', 'backend\ResponseController@colorsList');


//Admin Section
Route::group(['namespace' => 'backend\Admin', 'prefix' => 'admin'], function () {
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard-admin');
//    Route::post('confirm-transaction-password', 'DashboardController@confirmTransactionPassword');

    Route::get('notification', 'DashboardController@viewNotification')->name('admin-notification');
    Route::get('seen-notification', 'DashboardController@seenNotification');

    /*
 * Admin - E-Commerce
 * Namespace -
 * Prefix -
 */
    include('admin/ecommerce.php');

    /*
 * Admin - Merchant Master
 * Namespace -
 * Prefix -
 */
    include('admin/merchants.php');


    /*
 * Admin -Staff Master
 * Namespace Staff
 * Prefix staff
 */
    include('admin/staffs.php');


    /*
     * Admin Reports
     * Namespace Report
     * Prefix report
     */
    include('admin/reports.php');


    /*
 * Admin Customer Master
 * Namespace -
 * Prefix -
 */
    include('admin/customers.php');

    /*
 * Admin Configurations
 * Namespace -
 * Prefix -
 */
    include('admin/configurations.php');


});

//Merchant Section
Route::group(['namespace' => 'backend\Merchant', 'prefix' => 'merchant'], function () {
    Route::get('/dashboard', 'DashboardController@dashboard')->name('merchant/dashboard');
    Route::get('/success', 'DashboardController@success')->name('merchant/success');
    Route::get('notification', 'DashboardController@viewNotification')->name('merchant-notification');
    Route::get('seen-notification', 'DashboardController@seenNotification');

//    Route::post('confirm-transaction-password', 'DashboardController@confirmTransactionPassword');

//    Route::get('view-merchant', 'ProfileController@viewMerchant')->name('merchant-profile');
    Route::get('edit-merchant', 'ProfileController@editMerchant')->name('merchant-edit-merchant-id');
    Route::post('submit-merchant-profile-edit', 'ProfileController@submitProfileEdit')->name('merchant-submit-merchant-profile-edit');
    Route::post('submit-merchant-image-edit', 'ProfileController@updateImage')->name('merchant-submit-image-edit');
    Route::post('merchant-submit-doc', 'ProfileController@merchantDoc')->name('merchant-submit-doc');
    Route::post('delete-doc/{id}', 'ProfileController@deleteDoc')->name('delete-doc');
    Route::post('submit-merchant-pass', 'ProfileController@submitPasswordEdit')->name('merchant-submit-merchant-pass');
//    Route::post('submit-merchant-transaction-pass', 'ProfileController@submitTrannsactionPasswordEdit')->name('merchant-submit-merchant-transaction-pass');

    Route::group(['prefix' => 'product'], function () {
        //product
        Route::get('view', 'ProductController@viewProduct')->name('view-product-merchant');
        Route::get('request', 'ProductController@viewProductRequest')->name('view-product-request-merchant');
        Route::get('create', 'ProductController@createProduct')->name('create-product-merchant');
        Route::get('standard', 'ProductController@standardProducts')->name('create-product-merchant-standard');
        Route::post('standard/{id}', 'ProductController@createStandardProducts')->name('create-product-merchant-standard-post');
        Route::get('edit/{slug}', 'ProductController@editProduct')->name('edit-product-merchant');
        Route::post('create-product-first', 'ProductController@createProductFirst')->name('create-product-first');

        Route::get('edit/{slug}', 'ProductController@editProductGeneralTab')->name('edit-product-merchant');
        Route::get('i-edit/{slug}', 'ProductController@editProductImageTab')->name('image-edit-product-merchant');
        Route::get('v-edit/{slug}', 'ProductController@editProductVariantTab')->name('variant-edit-product-merchant');
        Route::get('s-edit/{slug}', 'ProductController@editProductSpecsTab')->name('specs-edit-product-merchant');

//        Specifications
        Route::post('s-edit/{id}/add', 'ProductController@addSpecs')->name('add-specs-product');
        Route::post('s-edit/{id}/update', 'ProductController@updateSpecs')->name('update-specs-product');
        Route::post('s-edit/{id}/delete', 'ProductController@deleteSpecs')->name('delete-spec-product');

        Route::post('edit/{id}', 'ProductController@editProductPost')->name('edit-product-merchant-post');

        Route::post('add-product-images/{id}', 'ProductController@addProductImages')->name('add-product-images-merchant');
        Route::post('add-product-variant/{id}', 'ProductController@addProductVariant')->name('add-product-variant-merchant');

        Route::post('update-product-variant/image', 'ProductController@updateProductVariantImage')->name('update-product-variant-image');
        Route::post('update-product-variant', 'ProductController@updateProductVariant');

        Route::post('edit-product-variant', 'ProductController@editProductVariant')->name('edit-product-variant-merchant');
        Route::get('delete-product-variant/{id}', 'ProductController@deleteProductVariant')->name('delete-product-variant');
        Route::post('delete-product-variant/{id}', 'ProductController@deleteProductVariant')->name('delete-product-variant-post');
        Route::get('delete-product-image/{id}', 'ProductController@deleteProductImage')->name('delete-product-image-merchant');

        Route::post('delete-product/{id}', 'ProductController@deleteProduct')->name('delete-product-merchant');

//        Route::get('get-search-list', 'ProductController@getSearchList');

        Route::get('merchant-featured-product-request/{id}', 'ProductController@merchantRequest')->name('merchant-featured-product-request');

        //(chinsese) chproduct

//        Route::get('edit-product-ch/{slug}', 'ProductController@editCHProduct')->name('edit-product-ch-merchant');
//        Route::post('edit-product-ch/{id}', 'ProductController@editCHProductPost')->name('edit-product-ch-merchant-post');
//        Route::post('edit-product-variant-ch', 'ProductController@editCHProductVariant')->name('edit-product-variant-ch-merchant');


        //(traditional-chinsese) tr-chproduct
//        Route::get('edit-product-tr-ch/{slug}', 'ProductController@editTRCHProduct')->name('edit-product-tr-ch-merchant');
//        Route::post('edit-product-tr-ch/{id}', 'ProductController@editTRCHProductPost')->name('edit-product-tr-ch-merchant-post');
//        Route::post('edit-product-variant-tr-ch', 'ProductController@editTRCHProductVariant')->name('edit-product-variant-tr-ch-merchant');


    });


    Route::group(['prefix' => 'order'], function () {
        Route::get('', 'OrderController@manageorder')->name('manage-order');
        Route::get('detail/{id}', 'OrderController@orderdetails')->name('order-details');
        Route::post('item/status/{id}', 'OrderController@itemStatusChange')->name('item-status-change');
        Route::post('item/shipping/{id}', 'OrderController@itemShipping')->name('item-shipping');
    });


    Route::group(['prefix' => 'payment'], function () {
//        Route::get('', 'PaymentController@managePayment')->name('manage-payment');
//        Route::get('list', 'PaymentController@managePaymentList')->name('manage-payment-list');
        Route::get('check-customer', 'PaymentController@checkCustomer');
        Route::post('qr-check-customer', 'PaymentController@qrCheckCustomer')->name('qr-check-customer');
//        Route::post('payment-request', 'PaymentController@paymentRequest')->name('merchant-payment-request');
//        Route::post('cancel-request/{id}', 'PaymentController@cancelRequest');


//        Route::get('bonus-request-cash', 'PaymentController@bonusRequestCash')->name('bonus-request-cash');
//        Route::post('bonus-request-cash/{id}', 'PaymentController@submitAdminBonus');

        Route::get('wallet-transfer', 'PaymentController@walletTransfer')->name('merchant-customer-wallet-transfer');
        Route::post('wallet-transfer', 'PaymentController@walletTransferPost');

//        Route::get('check-merchant', 'PaymentController@checkMerchant');
//        Route::post('qr-check-merchant', 'PaymentController@qrCheckMerchant')->name('qr-check-merchant');
//        Route::get('wallet-transfer/merchant', 'PaymentController@walletTransferMerchant')->name('merchant-merchant-wallet-transfer');
//        Route::post('wallet-transfer/merchant', 'PaymentController@walletTransferMerchantPost');

    });

    Route::get('wallet-withdraw', 'PaymentController@walletWithdraw');
    Route::post('wallet-withdraw', 'PaymentController@submitWalletWithdraw');
    Route::get('edit-bank', 'PaymentController@editBank')->name('merchant-edit-bank');
    Route::post('update-bank', 'PaymentController@updateBank')->name('merchant-update-bank');

    Route::group(['prefix' => 'report'], function () {
//        Route::get('payment-report', 'ReportController@paymentReport')->name('payment-reportmerchant');
        Route::get('order-report', 'ReportController@orderReport')->name('order-reportmerchant');
        Route::get('wallet-report', 'ReportController@walletReport')->name('wallet-reportmerchant');
        Route::get('purchase-report', 'ReportController@purchaseReport')->name('purchase-reportmerchant');
        Route::get('wallet-transfer-report', 'ReportController@walletTransferReport')->name('wallet-transfer-reportmerchant');

        Route::get('cash-withdraw-report', 'ReportController@cashWithdrawReport')->name('merchant-cash-withdraw-report');
//        Route::get('merchant-grant-retain-report', 'ReportController@grantRetainReport')->name('merchant-grant-retain-report');


    });
});

//Frontend Section
Route::group(['namespace' => 'frontend\Shop', 'middleware' => 'multiLog'], function () {
    Route::get('/', 'HomeController@home');
    Route::get('bid-win', 'HomeController@bidWin');
    Route::get('cart-view', 'HomeController@cartView')->name('cart-view');
    Route::get('product-quick-view/{slug}', 'HomeController@quickView');

    Route::get('save-subscriber', 'HomeController@saveSubscriber');

//    Route::get('sell-on', 'HomeController@sellOnGoldenGate')->name('home-sell-on');
//    Route::get('become-affiliate', 'HomeController@becomeAffiliate')->name('home-become-affiliate');
//    Route::get('upgrade', 'HomeController@upgradeToMember')->name('upgrade-to-member');
//    Route::post('upgrade', 'HomeController@upgradeToMemberPost')->name('upgrade-to-member-post');

//    terms and condition / privacy policy
    Route::get('privacy-policy', 'HomeController@privacyPolicy')->name('home-privacy-policy');
    Route::get('terms-of-use', 'HomeController@termmOfUse')->name('home-terms-of-use');


    //About
    Route::get('about', 'HomeController@aboutus')->name('about');
    //Contact
    Route::get('contact', 'HomeController@contactus')->name('contact');

    //Blog
    Route::get('blog', 'HomeController@blog')->name('blog');
    Route::get('single-blog', 'HomeController@singleBlog')->name('single-blog');


    Route::get('login', 'LoginController@loginPage')->name('checkout-login');
    Route::post('login/customer', 'LoginController@loginCustomer')->name('login-customer');
    Route::get('customer-logout', 'LoginController@frontendLogout')->name('customer-logout');
    Route::get('register', 'LoginController@registerPage')->name('customer-register');
    Route::post('register', 'LoginController@registerPagePost')->name('customer-register-post');


//    login with facebook
    Route::get('login/facebook', 'FacebookController@redirectToFacebook');
    Route::get('login/facebook/callback', 'FacebookController@handleFacebookCallback');


    Route::post('confirm-transaction-password', 'LoginController@confirmTransactionPassword');

    Route::get('customer-verify', 'LoginController@customerVerify')->name('customer-verify');
    Route::post('customer-verify', 'LoginController@customerVerifyPost')->name('customer-verify-post');
    Route::get('verify-email/{token?}', 'LoginController@verifyEmail')->name('verify-email');

    // merchant email verification
    Route::get('customer-verify', 'LoginController@customerVerify')->name('customer-verify');
    Route::post('customer-verify', 'LoginController@customerVerifyPost')->name('customer-verify-post');
    Route::get('verify-email/merchant/{token?}', 'LoginController@verifyEmailMerchant')->name('merchant-verify-email');

    Route::get('product/{slug}', 'ProductController@detail');
    Route::get('product-category', 'ProductController@productCategory')->name('product-by-category');

//    Route::get('list/product', 'ProductController@productlist')->name('product-list');


    //Merchant Info
    Route::get('merchant-detail/{slug}', 'HomeController@merchant')->name('merchant-info');
    Route::get('list/merchant', 'HomeController@merchantlist')->name('merchant-list');

//    Route::get('/free-register', 'MerchantRegisterController@showMerchantRegisterForm')->name('free-merchant-register');
//    Route::post('/free-register', 'MerchantRegisterController@postMerchantRegisterForm')->name('free-merchant-register-post');
//
//    Route::get('/independent-register', 'MerchantRegisterController@showIndependentMerchantRegisterForm')->name('independent-merchant-register');
//    Route::post('/independent-register', 'MerchantRegisterController@postIndependentMerchantRegisterForm')->name('independent-merchant-register-post');


    //Search Merchant Product
    Route::get('search-merchant-product', 'HomeController@searchMerchantProduct')->name('search-merchant-product');


    //Search Merchant
    //Search Product

    Route::get('search', 'HomeController@search')->name('search-product');
    Route::get('categories', 'HomeController@allCategories')->name('all-categories');


    //Cart Management
    Route::get('/cart', 'CartController@index')->name('cart.index');
    Route::post('/cart/{product}', 'CartController@store')->name('cart.store');
    Route::patch('/cart/{product}', 'CartController@update')->name('cart.update');
    Route::delete('/cart/{product}', 'CartController@destroy')->name('cart.destroy');

    Route::get('/clear-cart', 'CartController@destroyAll')->name('clear-cart-checkout');

    //ajax for cart.js
    Route::get('api/get-cart-session', 'CartController@getCartSession');
    Route::post('api/cart-remove-product', 'CartController@removeCartProduct');


    //ajax for cart-checkout.js
    Route::get('checkout-item-up', 'CartController@checkoutItemUp');
    Route::get('checkout-item-down', 'CartController@checkoutItemDown');


    //Order Management
    Route::get('order-list', 'OrderController@orderList')->name('order-list');

    Route::get('order/address', 'OrderController@address')->name('order-address');
    Route::post('order/address', 'OrderController@postAddress')->name('post-order-address');
    Route::get('order-detail/{id}', 'OrderController@orderDetail')->name('order-detail');
    Route::post('confirm-order/{id}', 'OrderController@confirmOrder')->name('confirm-order');
    Route::post('cancel-order/{id}', 'OrderController@cancelOrder')->name('cancel-order');

//    payment
//    Route::get('make-payment', 'PaymentController@paymentForm')->name('make-payment');
//    Route::post('manual-payment', 'PaymentController@manualMakePayment')->name('manual-make-payment');
//    Route::get('merchant-exist', 'PaymentController@merchantExist');
//    Route::post('qr-merchant-exist', 'PaymentController@qrMerchantExist')->name('qr-merchant-exist');
//    Route::post('qr-payment', 'PaymentController@qrMakePayment')->name('qr-make-payment');
//    Route::post('confirm-payment', 'PaymentController@confirmPay')->name('confirm-payment');
//    Route::post('payment-detail', 'OrderController@paymentDetail')->name('payment-detail');
//    Route::post('payment-submit', 'OrderController@paymentSubmit')->name('payment-submit');

//    customer wallet transfer
    Route::get('my-wallet', 'TransferController@myWallet')->name('my-wallet');
    Route::get('my-reports/{report_type?}', 'ReportController@allReport')->name('my-reports');

//    Route::get('make-transfer', 'TransferController@transferForm')->name('make-transfer');
//    Route::get('customer-exist', 'TransferController@customerExist');
//    Route::post('qr-user-exist', 'TransferController@qrCustomerExist')->name('qr-user-exist');
//    Route::post('manual-transfer', 'TransferController@manualMakeTransfer');
//    Route::post('qr-transfer', 'TransferController@qrMakeTransfer');


    Route::post('accept-request/{id}', 'PaymentController@acceptRequest')->name('accept-request-payment');
    Route::post('decline-request/{id}', 'PaymentController@declineRequest')->name('decline-request-payment');

//    password recovery
    Route::get('recovery', 'LoginController@passRecoveryForm')->name('frontend-recovery');
    Route::post('recovery', 'LoginController@passRecoveryPost')->name('post-frontend-recovery');
    Route::get('reset-password/{token?}', 'LoginController@resetPasswordForm')->name('frontend-reset');
    Route::post('reset-password/{token?}', 'LoginController@resetPasswordPost')->name('post-frontend-reset');

});

//    password recovery
Route::get('b-recovery/{type}', 'backend\PassResetController@passRecoveryForm')->name('b-recovery');
Route::post('b-recovery/{type}', 'backend\PassResetController@passRecoveryPost')->name('b-post-recovery');
Route::get('b-reset-password/{type}/{token?}', 'backend\PassResetController@resetPasswordForm')->name('b-reset');
Route::post('b-reset-password/{type}/{token?}', 'backend\PassResetController@resetPasswordPost')->name('b-post-reset');


Route::post('regen-user-qr', 'backend\RegenQRController@memberQR')->name('regen-user-qr');
Route::post('regen-merchant-qr', 'backend\RegenQRController@merchantQR')->name('regen-merchant-qr');


Route::get('/dev/refresh', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo 'config cleared and cached';
});

