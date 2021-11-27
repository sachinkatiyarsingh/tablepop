<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// AuthController

Route::get('login','Auth\AuthController@login');
Route::get('forget','Auth\AuthController@forget');
Route::post('forget-insert','Auth\AuthController@forget_insert');
Route::get('reset-password/{id}','Auth\AuthController@reset');
Route::post('reset_password_insert/{id}','Auth\AuthController@reset_password_insert');
Route::post('authenticate','Auth\AuthController@authenticate');


Route::group(['middleware' => 'checklogin'], function () {

    //AdminController

    Route::get('/','Auth\AdminController@dashboard');
    Route::get('admin-setting','Auth\AdminController@adminSetting');
    Route::post('admin-setting-insert','Auth\AdminController@adminSettingInsert');
    Route::post('dashboardweek','Auth\AdminController@dashboardweek');
    Route::post('dashboardmonth','Auth\AdminController@dashboardmonth');
    Route::get('notification','Auth\AdminController@notifications');
    Route::get('ongoingData','Auth\AdminController@ongoingData');
    Route::get('messagesdata','Auth\AdminController@MessageData');
    Route::post('notification-status','Auth\AdminController@notificationStatus');


    Route::get('profile','Auth\AdminController@profile');
    Route::post('profile/insert','Auth\AdminController@profile_insert');
    Route::get('change-password','Auth\AdminController@change_password');
    Route::post('change-password/insert','Auth\AdminController@change_password_insert');
    Route::get('logout','Auth\AdminController@logout');

    //CustomerController
    
    Route::group(['middleware' => 'permissions'], function () {
        Route::get('customers','CustomerController@index');
        Route::match(['get', 'post'],'customers-add','CustomerController@add');
        Route::post('create-customer','CustomerController@create_customer');
        Route::match(['get', 'post'],'customers-edit/{id}','CustomerController@edit');
        Route::post('customers-update/{id}','CustomerController@update');
        Route::post('customers-verify','CustomerController@delete');
    });
    
    
    Route::get('customers/states/{id}','CustomerController@states');
    Route::post('customers-details','CustomerController@customers');
    Route::get('contactus','CustomerController@contactus');

    

    //StaffController
    Route::get('staff','StaffController@index');
    Route::get('staff-add','StaffController@add');
    Route::post('create-staff','StaffController@create');
    Route::get('staff-edit/{id}','StaffController@edit');
    Route::post('staff-update/{id}','StaffController@update');
    Route::get('staff-delete/{id}','StaffController@delete');

    //QuestionnaireController
    Route::group(['middleware' => 'permissions'], function () {
        Route::get('event-list','QuestionnaireController@index');
        Route::get('event-list/{id}','QuestionnaireController@details');
        Route::get('planner-list/{id}','QuestionnaireController@plannerList'); 
    }); 
    Route::post('send-mail','QuestionnaireController@sendMail');
    Route::post('questionnaire-data','QuestionnaireController@questionnaireData');
    Route::get('event-details/{id}','QuestionnaireController@eventsDetails');
    Route::POST('milestone-payment','QuestionnaireController@milestonePayment');

    
    //EventController
   Route::get('event-types','EventController@index');
   Route::match(['get', 'post'],'event-types-add','EventController@add');
   Route::match(['get', 'post'],'event-types-edit/{id}','EventController@edit');
   Route::get('event-types-delete/{id}','EventController@delete');
   
   //ThemeController
   Route::get('themes','ThemeController@index');
   Route::match(['get', 'post'],'themes-add','ThemeController@add');
   Route::match(['get', 'post'],'themes-edit/{id}','ThemeController@edit');
   Route::get('themes-delete/{id}','ThemeController@delete');
   
    
   //VendorController
    
   Route::group(['middleware' => 'permissions'], function () {
    Route::match(['get', 'post'],'vendors','VendorController@index');
        Route::match(['get', 'post'],'vendors-add','VendorController@add');
        Route::post('create-customer','VendorController@create_customer');
        Route::match(['get', 'post'],'vendors-edit/{id}','VendorController@edit');
        Route::post('vendors-update/{id}','VendorController@update');
        Route::get('vendors-delete/{id}','VendorController@delete');
        Route::get('vendors-profile/{id}','VendorController@profile');
    
   }); 
   Route::post('status','VendorController@status');
   Route::group(['middleware' => 'permissions'], function () {
    Route::match(['get', 'post'],'planners','PlannerController@index');
        Route::match(['get', 'post'],'planners-add','PlannerController@add');
        Route::match(['get', 'post'],'planners-edit/{id}','PlannerController@edit');
        Route::get('planners-delete/{id}','PlannerController@delete');
        Route::get('planner-profile/{id}','PlannerController@profile');
   }); 

   
   Route::get('calendar/{id}','CalendarController@fullCalendar');
   Route::group(['middleware' => 'permissions'], function () {
      Route::get('calendar','CalendarController@index');
    });

    //MessageController
    Route::get('message','MessageController@index');
    Route::post('message-list','MessageController@messsageListShow');
    Route::post('message-send','MessageController@messageSend');
    Route::post('message-image-upload','MessageController@imageUpload');
    Route::post('remove-image','MessageController@removeImage');

   //BlogController
   Route::group(['middleware' => 'permissions'], function () {
        Route::get('blogs','BlogController@index');
        Route::match(['get', 'post'],'blog-add','BlogController@add');
        Route::match(['get', 'post'],'blog-edit/{id}','BlogController@edit');
        Route::get('blog-delete/{id}','BlogController@delete');
   });
   Route::post('blog-image-upload','BlogController@imageUpload');
   
   Route::post('file-delete','BlogController@fileDelete');
   Route::get('blog-edit/blog-file-delete/{id}','BlogController@blogFileDelete');


   //FaqController
    Route::group(['middleware' => 'permissions'], function () {
        Route::get('faq','FaqController@index');
        Route::match(['get', 'post'],'faq-add','FaqController@add');
        Route::match(['get', 'post'],'faq-edit/{id}','FaqController@edit');
        Route::get('faq-delete/{id}','FaqController@delete');
    });
   
     //TransactionController
     Route::match(['get', 'post'],'history','TransactionController@history');
    Route::post('refund','TransactionController@paymentRefunded');
    Route::post('get-event-list','TransactionController@getEventList');
   
});
