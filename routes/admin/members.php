<?php


Route::group(['middleware' => 'staff_permission:2.Member Master.Add New Member'], function () {
    Route::get('add-new-member', 'MemberController@showRegister')->middleware('admin');
});

Route::group(['middleware' => 'staff_permission:1.Member Master.List'], function () {
    Route::get('memberLists', 'MemberController@memberLists')->middleware('admin');
});

Route::group(['middleware' => 'staff_permission:1.Member Master.Profile'], function () {
    Route::get('member-profile/{id}', 'MemberController@memberProfile')->middleware('admin');
});

Route::group(['middleware' => 'staff_permission:2.Member Master.Profile'], function () {

    Route::get('edit-member/{id}', 'MemberController@editMember')->middleware('admin');
    Route::get('grant-member-wallet/{id}', 'MemberController@showGrantWallet')->middleware('admin');
    Route::post('grant-member-wallet', 'MemberController@postGrantWallet')->name('grantMemberWallet')->middleware('admin');

    Route::get('retain-member-wallet/{id}', 'MemberController@showRetainWallet')->middleware('admin');
    Route::post('retain-member-wallet', 'MemberController@postRetainWallet')->name('retainMemberWallet')->middleware('admin');

});

Route::group(['middleware' => 'staff_permission:2.Member Master.Placement Tree'], function () {
//placement tree
    Route::get('standard-placement-tree', 'TreeViewController@Standardplacementtree')->middleware('admin');
    Route::get('auto-placement-tree', 'TreeViewController@Autoplacementtree')->middleware('admin');
    Route::get('special-placement-tree', 'TreeViewController@Specialplacementtree')->middleware('admin');
});

Route::group(['middleware' => 'staff_permission:2.Member Master.Upgrade Membership'], function () {
    Route::get('upgrade-customer', 'MemberController@upgradeCustomer')->middleware('admin');
});



