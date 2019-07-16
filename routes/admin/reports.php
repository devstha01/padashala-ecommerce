<?php
Route::group(['namespace' => 'Report', 'prefix' => 'report'], function () {

    Route::get('merchant-cash-withdraw-report', 'ReportController@merchantCashWithdrawReport')->name('admin-merchant-cash-withdraw-report')
        ->middleware('staff_permission:1.Reports.Merchant Cash Withdrawal Request');
    Route::get('merchant-cash-withdraw-detail/{id}', 'ReportController@merchantCashWithdrawDetail')->name('admin-merchant-cash-withdraw-detail')
        ->middleware('staff_permission:2.Reports.Merchant Cash Withdrawal Request');
    Route::post('merchant-cash-withdraw-edit/{id}', 'ReportController@merchantCashWithdrawEdit')->name('admin-merchant-cash-withdraw-edit')
        ->middleware('staff_permission:2.Reports.Merchant Cash Withdrawal Request');

//    Route::get('merchant-wallet-transfer-report', 'ReportController@merchantWalletTransferReport')->name('admin-merchant-wallet-transfer-report')
//        ->middleware('staff_permission:1.Reports.Merchant Wallet Transfer Report');
//    Route::get('merchant-payment-report', 'ReportController@merchantPaymentReport')->name('admin-merchant-payment-report')
//        ->middleware('staff_permission:1.Reports.Merchant Payment Report');

    Route::get('grant-retain-report', 'ReportController@grantRetainReport')->name('grant-retain-report')
        ->middleware('staff_permission:1.Reports.Grant Wallet/ Retain Wallet Report');
    Route::get('purchase-report', 'ReportController@purchaseReport')->name('admin-purchase-report')
        ->middleware('staff_permission:1.Reports.Product Purchase Report');

});
