<?php


Route::group(['namespace' => 'Ecommerce', 'prefix' => 'category'], function () {

//banner
    Route::get('banner', 'BannerController@bannerList')->name('admin-banner')
        ->middleware('staff_permission:1.Customer.Banners');

    Route::group(['middleware' => 'staff_permission:2.Customer.Banners'], function () {

        Route::get('add-banner', 'BannerController@addBanner')->name('admin-add-banner');
        Route::post('save-banner', 'BannerController@saveBanner')->name('admin-save-banner');

        Route::get('edit-banner/{id}', 'BannerController@editBanner')->name('admin-edit-banner');
        Route::get('status-banner/{id}', 'BannerController@statusBanner')->name('admin-status-banner');
        Route::post('update-banner/{id}', 'BannerController@updateBanner')->name('admin-update-banner');
        Route::post('destroy-banner/{id}', 'BannerController@destroy')->name('admin-destroy-banner');
    });
});

//Abouts
Route::group(['namespace' => 'Aboutus', 'prefix' => 'aboutus'], function () {

    Route::get('about', 'AboutController@index')->name('admin-about');
    Route::get('add-about-content', 'AboutController@createContent')->name('admin-add-about-content');
    Route::post('about-save-content', 'AboutController@saveContent')->name('admin-save-about-content');
    Route::get('edit-about-content/{id}', 'AboutController@editContent')->name('admin-edit-about-content');
    Route::post('update-content/{id}', 'AboutController@updateContent')->name('admin-update-about-content');
    Route::get('destroy-content/{id}', 'AboutController@destroy')->name('admin-destroy-about-content');


    Route::group(['middleware' => 'staff_permission:1.Customer.List'], function () {
        Route::get('customer-list', 'AboutController@customerList')->name('customer-list');
        Route::get('customer-detail/{id}', 'AboutController@customerDetail')->name('customer-detail');
    });


//Subscribers
    Route::group(['middleware' => 'staff_permission:1.Customer.Subscribers'], function () {
        Route::get('subscribe', 'AboutController@subscribe')->name('admin-subscribe');
    });

    Route::group(['middleware' => 'staff_permission:2.Customer.Subscribers'], function () {
        Route::post('status-subscribe/{id}', 'AboutController@subscribeStatus')->name('admin-subscribe-status');
    });
});

//Blogs
Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function () {

    Route::get('blog', 'BlogController@index')->name('admin-blog');
    Route::get('add-blog-content', 'BlogController@createContent')->name('admin-add-blog-content');
    Route::post('blog-save-content', 'BlogController@saveContent')->name('admin-save-blog-content');

    Route::get('edit-blog-content/{id}', 'BlogController@editContent')->name('admin-edit-blog-content');
    Route::post('update-content/{id}', 'BlogController@updateContent')->name('admin-update-blog-content');
    Route::get('destroy-content/{id}', 'BlogController@destroy')->name('admin-destroy-blog-content');
});