<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\V1', 'middleware' => 'localization'], function () {



    //shangrilla web api's

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@get_settings');
    });

    Route::post('/signup', 'UserController@Signup');
    Route::post('/register', 'UserController@Register');
    Route::post('/userdetails', 'UserController@user_details');
    Route::post('login','UserController@login');
    Route::post('/booklist','UserController@Booklist');
    Route::post('/mybooks','UserController@Mybooks');
    Route::post('/cartlist','UserController@Cartlist');
    Route::post('/order','UserController@Order');
    Route::post('/publish','UserController@Publish');
    Route::post('/mypublishes','UserController@MypublishedBooks');
    Route::post('/comment','UserController@Comment');
    Route::post('/app_update','UserController@app_update');
    Route::post('/update_course','UserController@update_course');
    Route::post('/courselist','UserController@courselist');
    Route::post('/enrolled_course','UserController@enrolled_course');
    Route::post('/my_enrolled_course','UserController@my_enrolled_course');



    Route::group(['prefix' => 'booklist'], function () {
        Route::post('/searchbooks', 'UserController@Searchbook');
        Route::post('/add-cart','UserController@add_cart');
        Route::post('/delete-cart','UserController@delete_cart');
    
    });

});
