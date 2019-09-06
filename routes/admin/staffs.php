<?php
Route::group(['namespace' => 'Staff', 'prefix' => 'staff', 'middleware' => 'role_is_admin'], function () {

    Route::get('list', 'StaffController@listStaff')->name('admin-staff-list');


    Route::get('register', 'StaffController@showStaffRegisterForm')->name('admin-staff-register');
    Route::post('register', 'StaffController@postStaffRegisterForm')->name('admin-staff-register-post');

    Route::get('edit-staff/{id}', 'StaffController@editStaff')->name('edit-staff-id');
    Route::get('permission-staff/{id}', 'StaffController@permissionStaff')->name('edit-staff-permission');
    Route::get('change-status-staff/{id}', 'StaffController@changeStatus')->name('change-status-staff-admin');
    Route::post('change-permission-staff/{id}', 'StaffController@changePermission')->name('change-permission');

    Route::post('submit-staff-profile-edit/{id}', 'StaffController@submitProfileEdit')->name('submit-staff-profile-edit');
    Route::post('submit-staff-pass/{id}', 'StaffController@submitPasswordEdit')->name('submit-staff-pass');
//    Route::post('submit-staff-transaction-pass/{id}', 'StaffController@submitTrannsactionPasswordEdit')->name('submit-staff-transaction-pass');

    Route::get('top-up', 'StaffController@topUp')->name('top-up');
    Route::post('top-up', 'StaffController@topUpPost')->name('top-up-post');

});
