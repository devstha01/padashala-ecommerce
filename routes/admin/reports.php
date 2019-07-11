<?php
Route::group(['namespace' => 'Report', 'prefix' => 'report'], function () {

//    Route::get('member-cash-withdraw-report', 'ReportController@memberCashWithdrawReport')->name('admin-member-cash-withdraw-report')
//        ->middleware('staff_permission:1.Reports.Member Cash Withdrawal Request');
//    Route::get('member-cash-withdraw-detail/{id}', 'ReportController@memberCashWithdrawDetail')->name('admin-member-cash-withdraw-detail')
//        ->middleware('staff_permission:2.Reports.Member Cash Withdrawal Request');
//    Route::post('member-cash-withdraw-edit/{id}', 'ReportController@memberCashWithdrawEdit')->name('admin-member-cash-withdraw-edit')
//        ->middleware('staff_permission:2.Reports.Member Cash Withdrawal Request');

    Route::get('merchant-cash-withdraw-report', 'ReportController@merchantCashWithdrawReport')->name('admin-merchant-cash-withdraw-report')
        ->middleware('staff_permission:1.Reports.Merchant Cash Withdrawal Request');
    Route::get('merchant-cash-withdraw-detail/{id}', 'ReportController@merchantCashWithdrawDetail')->name('admin-merchant-cash-withdraw-detail')
        ->middleware('staff_permission:2.Reports.Merchant Cash Withdrawal Request');
    Route::post('merchant-cash-withdraw-edit/{id}', 'ReportController@merchantCashWithdrawEdit')->name('admin-merchant-cash-withdraw-edit')
        ->middleware('staff_permission:2.Reports.Merchant Cash Withdrawal Request');
//
//    Route::get('member-wallet-convert-report', 'ReportController@memberWalletConvertReport')->name('admin-member-wallet-convert-report')
//        ->middleware('staff_permission:1.Reports.Member Wallet Convert Report');
//    Route::get('member-wallet-transfer-report', 'ReportController@memberWalletTransferReport')->name('admin-member-wallet-transfer-report')
//        ->middleware('staff_permission:1.Reports.Member Wallet Transfer Report');
    Route::get('merchant-wallet-transfer-report', 'ReportController@merchantWalletTransferReport')->name('admin-merchant-wallet-transfer-report')
        ->middleware('staff_permission:1.Reports.Merchant Wallet Transfer Report');
    Route::get('merchant-payment-report', 'ReportController@merchantPaymentReport')->name('admin-merchant-payment-report')
        ->middleware('staff_permission:1.Reports.Merchant Payment Report');
//    Route::get('monthly-bonus-report', 'ReportController@monthlyBonusReport')->name('monthly-bonus-report')
//        ->middleware('staff_permission:1.Reports.Monthly Bonus Report');
//    Route::get('shop-point-report', 'ReportController@shopPointReport')->name('admin-shop-point-withdraw-report')
//        ->middleware('staff_permission:1.Reports.Shopping Point Transform Report');
    Route::get('grant-retain-report', 'ReportController@grantRetainReport')->name('grant-retain-report')
        ->middleware('staff_permission:1.Reports.Grant Wallet/ Retain Wallet Report');
    Route::get('purchase-report', 'ReportController@purchaseReport')->name('admin-purchase-report')
        ->middleware('staff_permission:1.Reports.Product Purchase Report');

});
