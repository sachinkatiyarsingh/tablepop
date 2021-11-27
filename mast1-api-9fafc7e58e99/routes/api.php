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
Route::group(['middleware' => 'cors'], function () {
//CustomerController 
Route::post('social-register', 'API\CustomerController@socialRegister');
Route::post('sign-up', 'API\CustomerController@register');
Route::post('login', 'API\CustomerController@login');
Route::post('customer-profile', 'API\CustomerController@profile');
Route::post('forget', 'API\CustomerController@forget');
Route::post('reset-password/{id}', 'API\CustomerController@reset_password');
Route::post('customer-change-password', 'API\CustomerController@change_password');
Route::post('customer-notification', 'API\CustomerController@customerNotification');
Route::post('customer-dashboard', 'API\CustomerController@dashboard');
Route::post('customer-events-offfer', 'API\CustomerController@offerCustomer');
Route::post('cusromer-events-details', 'API\CustomerController@cusromerEventsDetails');
Route::post('cusromer-vendor-list', 'API\CustomerController@customerVendorList');
Route::post('check-product-quantity', 'API\CustomerController@checkProductQuantity');
Route::post('customer-dashboard-notification', 'API\CustomerController@customerDashboardNotification');
Route::post('customer-dashboard-ongoing', 'API\CustomerController@customerDashboardOngoing');
Route::post('customer-dashboard-message', 'API\CustomerController@customerDashboardMessage');
Route::post('cusromer-transaction-events', 'API\CustomerController@cusromerEvents');
Route::post('cusromer-transaction-event-details', 'API\CustomerController@cusromerTransactionEventDatils');
Route::post('customer-moodboards-list', 'API\CustomerController@customermoodboardsList');
Route::post('moodboards-album', 'API\CustomerController@moodboardsAlbum');
Route::post('select-album-image', 'API\CustomerController@selectAlbumImage');
Route::post('customer-select-vendor', 'API\CustomerController@customerSelectVendor');
Route::post('event-sellers', 'API\CustomerController@eventSellers');
Route::post('mark-favorite', 'API\CustomerController@markFavorite');
Route::post('unmark-favorite', 'API\CustomerController@unmarkFavorite');
Route::post('favorite-sellers', 'API\CustomerController@favoriteSellers');
Route::post('submit-review', 'API\CustomerController@submitReview');
Route::post('share-event', 'API\CustomerController@shareEvent');
Route::post('customer-notification-count', 'API\CustomerController@notificationCount');
Route::post('review-edit', 'API\CustomerController@reviewEdit');



//ApiController

Route::post('country', 'API\ApiController@country');
Route::post('states', 'API\ApiController@states');
Route::post('is-token', 'API\ApiController@isToken');
Route::post('seller-token', 'API\ApiController@isSellerToken');
Route::post('themes', 'API\ApiController@themes');
Route::post('event-type', 'API\ApiController@eventType');
Route::post('notification-delete', 'API\ApiController@notificationDelete');
Route::post('industry', 'API\ApiController@MerchantCode');
Route::post('blogs', 'API\ApiController@blogs');
Route::post('blog-details', 'API\ApiController@blogDetails');
Route::post('address-edit', 'API\ApiController@addressEdit');
Route::post('address-delete', 'API\ApiController@addressDelete');
Route::post('address', 'API\ApiController@address');
Route::post('address-add', 'API\ApiController@addressAdd');
Route::post('faq', 'API\ApiController@faq');
Route::post('contactus', 'API\ApiController@contactus');
Route::post('subscription', 'API\ApiController@subscription');
Route::post('questionnaire-event-type', 'API\ApiController@questionnaireEventType');
Route::post('venue', 'API\ApiController@venue');
Route::post('service-category', 'API\ApiController@serviceCategory');
Route::post('service-subcategory', 'API\ApiController@serviceSubCategory');

//QuestionnaireController
Route::post('questionnaire', 'API\QuestionnaireController@questionnaire');
Route::post('questionnaire-details', 'API\QuestionnaireController@questionnaireDetails');
Route::post('questionnaire-update', 'API\QuestionnaireController@questionnaireUpdate');
Route::post('questionnaire-list', 'API\QuestionnaireController@questionnaireList');
Route::post('image-delete', 'API\QuestionnaireController@imageDelete');
Route::post('questionnaire-planner', 'API\QuestionnaireController@questionnairePlanner');
Route::post('questionnaire-planner-details', 'API\QuestionnaireController@questionnairePlannerDetails');
Route::post('event-details', 'API\QuestionnaireController@eventDetails');
Route::post('interaction-date', 'API\QuestionnaireController@interactionDate');
Route::post('plannar-complete-event', 'API\QuestionnaireController@plannarCompleteEvent');


//SellerController
Route::post('planner', 'API\SellerController@planners');
Route::post('vendor', 'API\SellerController@vendors');
Route::post('seller-login', 'API\SellerController@sellerLogin');
Route::post('profile-update', 'API\SellerController@profileUpdate');
Route::post('seller-contract', 'API\SellerController@sellerContract');
Route::post('profile-details', 'API\SellerController@profileDetails');
Route::post('project-image', 'API\SellerController@projectImage');
Route::post('project-image-edit', 'API\SellerController@projectImageEdit');
Route::post('project-image-delete', 'API\SellerController@projectImageDelete');
Route::post('seller-change-password', 'API\SellerController@change_password');
Route::post('seller-forget', 'API\SellerController@forget');
Route::post('seller-reset-password/{id}', 'API\SellerController@resetPassword');
Route::post('create-milestones/{id}', 'API\SellerController@createMilestones');
Route::post('milestone-status', 'API\SellerController@milestoneStatus');
Route::post('milestones', 'API\SellerController@milestones');
Route::post('seller-transaction-events', 'API\SellerController@sellerTransactionEvents');
Route::post('seller-transaction-event-datails', 'API\SellerController@sellerIdTransactionEventDatils');
Route::post('moodboards', 'API\SellerController@moodboards');
Route::post('moodboard-datails', 'API\SellerController@moodboardsDetails');
Route::post('moodboards-add', 'API\SellerController@moodboardsAdd');
Route::post('moodboards-edit', 'API\SellerController@moodboardsEdit');
Route::post('moodboards-delete', 'API\SellerController@moodboardsDelete');
Route::post('moodboards-image-add', 'API\SellerController@moodboardsImageAdd');
Route::post('moodboards-image-delete', 'API\SellerController@moodboardsImageDelete');
Route::post('stripe-account', 'API\SellerController@stripeAccount');
Route::post('calendar', 'API\SellerController@calendar');

 


//PlannerController
Route::post('planner-plans', 'API\PlannerController@planList');
Route::post('planner-plan-add', 'API\PlannerController@planAdd');
Route::post('planner-plan-edit-data', 'API\PlannerController@planEditData');
Route::post('planner-plan-edit', 'API\PlannerController@planEdit');
Route::post('planner-plan-delete', 'API\PlannerController@planDelete');
Route::post('planner-dashboard', 'API\PlannerController@plannerDashboard');
Route::post('planner-events-list', 'API\PlannerController@plannerEventsList');
Route::post('planner-event-details', 'API\PlannerController@plannerEventsDetails');
Route::post('offers', 'API\PlannerController@offers');
Route::post('offer-create', 'API\PlannerController@offerCreate');
Route::post('offer-delete', 'API\PlannerController@offerDelete');
Route::post('offer-customer-list', 'API\PlannerController@offerCustomerList');
Route::post('offer-details', 'API\PlannerController@offerDetails');
Route::post('planner-dashboard-notification', 'API\PlannerController@plannerDashboardNotification');
Route::post('planner-dashboard-message', 'API\PlannerController@plannerDashboardMessgage');
Route::post('planner-dashboard-ongoing', 'API\PlannerController@plannerDashboardOngoing');
Route::post('planner-vendor-lists', 'API\PlannerController@vendorLists');
Route::post('planner-select-vendors', 'API\PlannerController@selectVendors');
Route::post('seller-notification-count', 'API\PlannerController@notificationCount');

//PaymentController
Route::post('customer-payment', 'API\PaymentController@customerPayment');
Route::post('offer-payment', 'API\PaymentController@offerPayment');
Route::post('payment-details', 'API\PaymentController@paymentDetails');


Route::post('product-payment', 'API\PaymentController@productPayment');
Route::get('pdf', 'API\PaymentController@pdf');


//VendorController
Route::post('products', 'API\VendorController@products');
Route::post('product-details', 'API\VendorController@productDetails');
Route::post('product-add', 'API\VendorController@productAdd');
Route::post('product-edit', 'API\VendorController@productEdit');
Route::post('product-delete', 'API\VendorController@productDelete');
Route::post('product-image-delete', 'API\VendorController@productImageDelete');
Route::post('vendor-profile', 'API\VendorController@vendorProfile');
Route::post('vendor-product', 'API\VendorController@vendorproducts');
Route::post('vendor-milestones', 'API\VendorController@milestones');



//MessageController
Route::post('messaging-sellers-list', 'API\MessageController@sellerList');
Route::post('customer-chat', 'API\MessageController@customerSellerChat');
Route::post('seller-customers-list', 'API\MessageController@customerList');
Route::post('seller-chat', 'API\MessageController@sellerCustomerChat');
Route::post('customer-seller-messages', 'API\MessageController@customerSellerMessaging');
Route::post('message-soket', 'API\MessageController@messageSoket');
Route::post('group-socket', 'API\MessageController@groupSocket');
Route::post('seller-messaging-list', 'API\MessageController@sellerMessaging');

//ProductOrderController
Route::post('add-to-cart', 'API\ProductOrderController@addToCart');
Route::post('cart-list', 'API\ProductOrderController@cartList');
Route::post('cart-remove-prodct', 'API\ProductOrderController@catrRemoveProdct');
Route::post('product-booking', 'API\ProductOrderController@productBooking');
});
