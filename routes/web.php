<?php

use App\Http\Controllers\DiscountController;
use App\Store;

// Route::get('runomidascript' , function (Request $request){
//     try{
//     $stores = Store::where('store_type' , 'product')->get();
//     foreach($stores as $store){
//         $user = $store->user;
//         $user->thumbnail_photo = $store->thumbnail_photo;
//         $user->save();
//     }
//     return "ok";
// }
// catch(\Exception $e){
//     return $e->getMessage();
// }

// });
//auth routes
Route::middleware(['guest:web'])->namespace('Auth')->group(function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('show.login.form');
    Route::post('/login', 'LoginController@doLogin')->name('do.login');
    Route::get('/register', 'RegisterController@showRegisterForm')->name('show.register.form');
    Route::post('/register', 'RegisterController@doRegister')->name('do.register');
    Route::get('/forget-password', 'ForgotPasswordController@showForgetPasswordForm')->name('show.forget.password.form');
    Route::post('/forget-password', 'ForgotPasswordController@forgetPassword')->name('forget.password');
    Route::post('/auth-user', 'ForgotPasswordController@authUser')->name('auth.user');
});
Route::middleware(['auth:web', 'redirectIfVerified'])->group(function () {
    Route::get('/verify-mobile', 'Auth\RegisterController@verifyMobilePage')->name('verify.mobile.page');
    Route::put('/verify-mobile', 'Auth\RegisterController@verifyMobile')->name('verify.mobile');
});

//admin routes
Route::prefix('admin')->group(function () {
    Route::get('/logout', 'AdminAuth\LoginController@adminLogout')->name('adminLogout');
    Route::middleware(['guest:admin'])->namespace('AdminAuth')->group(function () {
        Route::get('/login', 'LoginController@showLoginForm')->name('admin.login.show');
        Route::post('/login', 'LoginController@login')->name('admin.login');
    });
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/ads-panel/{ads}/delete', 'AdsController@delete')->name('delete.ads');
        Route::put('/position/{position}/price', 'AdsController@savePositionPrice')->name('position_price.update');
        Route::get('/ads/positions', 'AdsController@index')->name('ads_management');
        Route::put('/ads/{ads}/position' , 'AdsController@updatePosition')->name('ads.position.change');
        Route::get('/ads/{ads}/status/change' , 'AdsController@changeAdStatus')->name('ads.status.change');
        Route::get('/ads/new/create' , 'AdsController@adminCreate')->name('admin.ads.create');
        Route::post('/ads/new/store' , 'AdsController@store')->name('admin.ads.store');
        Route::get('/position/{position}/ads' , 'AdsController@adsIndex')->name('ads_list_management');
        Route::get('/ads/{ads}/show' , 'AdsController@show')->name('ads.show');
        Route::post('/ads/{ads}/update' , 'AdsController@update')->name('ads.update');
        Route::get('/users/{user}/ads' , 'AdsController@userAds')->name('ads_of_users');
        Route::post('/wallet/batch/charge' , 'WalletController@walletBatchCharge')->name('wallet.batch_charge');
        Route::get('get-city-by-province' , 'CityController@getCityByAjax')->name('admin.get_cities_by_province_id');
        Route::delete('reagent-code-delete/{reagent}', 'ReagentCodeController@delete')->name('delete.reagent.code');
        Route::get('/messages', 'MessageController@userList')->name('user.list');
        Route::get('/user/{user}/messages', 'MessageController@messages')->name('message.index');
        Route::post('/user/{user}/messages', 'MessageController@adminStore')->name('admin.store.message');
        Route::post('/user/batch-send-message', 'MessageController@batchSend')->name('batch.send.message');
        Route::delete('/message/{message}', 'MessageController@delete')->name('message.delete');
        Route::post('/wallet/charge-all-user', 'WalletController@chargeAllUser')->name('charge.all.user.wallet');
        Route::get('/user/export-mobile-excel', 'UsersController@exportAllMobileExcel')->name('export.all.users.mobile');
        Route::get('/store/export-mobile-excel', 'StoreController@exportAllMobileExcel')->name('export.all.stores.mobile');

        Route::get('/notifications', 'NotificationController@index')->name('notification.index');
        Route::get('/send-notification', 'NotificationController@sendNotificationFrom')->name('send.notification.form');
        Route::post('/send-notification', 'NotificationController@sendNotification')->name('send.notification');
        Route::get('/reports', 'ReportController@index')->name('report.index');
        Route::get('/change-password', 'AdminController@changePasswordForm')->name('showChangePasswordForm');
        Route::post('/change-password', 'AdminController@changePassword')->name('changeAdminPassword');

        Route::get('/dashboard', 'DashboardController@index')->name('adminDashboard');
        Route::get('/slider/products/search', 'ProductsController@searchNames')->name('searchNamesOfProducts');

        Route::get('/plans/list', 'PlanController@list')->name('listOfPlans');
        Route::post('/plans/create', 'PlanController@create')->name('createPlan');
        Route::get('/plans/{plan}/deactive', 'PlanController@deactive')->name('deactivePlan');
        Route::get('/plans/{plan}/active', 'PlanController@active')->name('activePlan');
        Route::put('/plans/update', 'PlanController@update')->name('updatePlan');

        Route::get('/slider/create', 'SliderController@createSlider')->name('createSlider');
        Route::post('/slider/create', 'SliderController@saveSlider')->name('saveSlider');
        Route::get('/slider/view', 'SliderController@viewSlider')->name('viewSlider');
        Route::get('/slider/{sliderId}/delete', 'SliderController@deleteSlider')->name('deleteSlider');
        Route::get('/slider/{sliderId}/edit', 'SliderController@editSlider')->name('editSlider');
        Route::post('/slider/{sliderId}/update', 'SliderController@updateSlider')->name('updateSlider');

        Route::get('/provinces', 'ProvinceController@showAllInAdminPanel')->name('showAllProvinces');
        Route::post('/provinces', 'ProvinceController@save')->name('saveProvinceInAdminPanel');
        Route::get('/province/{province}/delete', 'ProvinceController@delete')->name('deleteProvinceInAdminPanel');
        Route::put('/provinces/{province}', 'ProvinceController@update')->name('updateProvinceInAdminPanel');


        Route::get('/provinces/{province}/cities', 'CityController@showAllInAdminPanel')->name('showAllCitiesInAdminPanel');
        Route::post('/provinces/{province}/cities', 'CityController@save')->name('saveCityInAdminPanel');
        Route::put('/cities/{city}', 'CityController@update')->name('updateCityInAdminPanel');
        Route::get('/cities/{city}/delete', 'CityController@delete')->name('deleteCityInAdminPanel');

        Route::get('/users/show-list', 'UsersController@showList')->name('showListOfUsers');
        Route::delete('/user/{user}', 'UsersController@delete')->name('user.delete');
        Route::get('/users/show-banned-list', 'UsersController@showBannedList')->name('showBannedListOfUsers');
        Route::get('/users/{userId}/banned', 'UsersController@bennUser')->name('bennUser');
        Route::get('/users/{userId}/active', 'UsersController@activeUser')->name('activeUser');
        Route::get('/users/{userId}/edit', 'UsersController@editUser')->name('editUser');
        Route::post('/users/{userId}/update', 'UsersController@updateUser')->name('updateUser');
        Route::get('/users/create-user', 'UsersController@createUserPage')->name('createUserPage');
        Route::post('/users/create-user', 'UsersController@register')->name('registerUser');
        // Route::get('/attach-marketer/{user}', 'MarketerController@attachUser')->name('attach.marketer.user');
        // Route::get('/detach-marketer/{user}', 'MarketerController@detachUser')->name('detach.marketer.user');
        // Route::get('/list-of-marketer-user', 'MarketerController@index')->name('listOfMarketerUser');
        // Route::get('/marketer-wallet/{user}', 'WalletController@marketerWallet')->name('marketer.wallet');
        // Route::post('/marketer-checkout', 'WalletController@checkout')->name('checkout.marketer');
        // Route::get('marketer-export-excel', 'MarketerController@marketerExportExcel')->name('export.marketer.excel');

        Route::get('/user-wallet/{user}', 'WalletController@userWallet')->name('user.wallet.list');
        Route::get('/wallet-list', 'WalletController@index')->name('wallet.index');
        Route::post('/wallet', 'WalletController@store')->name('wallet.store');

        Route::get('/bills', 'BillController@index')->name('bills.index');
        Route::get('/bills/adminConfirmIndex', 'BillController@adminConfirmIndex')->name('bills.adminConfirmIndex');
        Route::post('/bills/adminConfirmBills', 'BillController@adminConfirmBill')->name('bills.adminConfirmBill');
        Route::patch('/bills/adminRejectBill/{bill}', 'BillController@adminRejectBill')->name('bills.adminRejectBill');
        Route::get('bills/make-delivered/ajax', 'BillController@makePainBack')->name('makePaidBackBillStatus');
        Route::get('/bills/{bill}/bill-item', 'BillItemController@show')->name('billItem.show');
        Route::get('/plan-subscriptions', 'PlanSubscriptionController@index')->name('showListOfPlanSubscription');

//        Route::get('/store-checkouts', 'CheckoutController@storeCheckout')->name('checkout.index');
        Route::get('/marketer-checkouts', 'CheckoutController@marketerCheckout')->name('marketer.checkout');
        Route::put('/store-checkouts/{checkout}', 'CheckoutController@storeCheckoutUpdate')->name('store.checkout.update');
        Route::put('/marketer-checkouts/{checkout}', 'CheckoutController@marketerCheckoutUpdate')->name('marketer.checkout.update');
//        Route::get('/checkouts/create', 'CheckoutController@create')->name('checkout.create');
//        Route::post('/checkouts', 'CheckoutController@store')->name('checkout.store');

        Route::get('checkout', 'CheckoutRequestsController@index')->name('checkoutrequest.index');
        Route::get('checkout/{checkoutRequests}/edit', 'CheckoutRequestsController@edit')->name('checkoutrequest.edit');
        Route::patch('checkout/{checkoutRequests}', 'CheckoutRequestsController@update')->name('checkoutrequest.update');
        Route::get('checkout/{checkoutRequests}', 'CheckoutRequestsController@destroy')->name('checkoutrequest.destroy');

        Route::get('/accounts-document', 'AccountingDocumentsController@index')->name('accountsDocument.index');
        Route::get('/credit-stores/excel/export', 'AccountingDocumentsController@exportCreditStores')->name('export_excel_file.credit_stores');
        Route::post('/submit-plan-subscriptions', 'AccountingDocumentsController@submitPlanDocument')->name('submitDocumentOfPlan');
        Route::post('/submit-bill', 'AccountingDocumentsController@submitBillDocument')->name('submitDocumentOfBill');
        Route::post('/submit-wallet', 'AccountingDocumentsController@submitWalletDocument')->name('submitDocumentOfWallet');
        Route::post('/submit-store-checkout', 'AccountingDocumentsController@submitStoreCheckoutDocument')->name('submitDocumentOfCheckout');
        // Route::post('/submit-marketer-checkout', 'AccountingDocumentsController@submitMarketerCheckoutDocument')->name('submitMarketerDocumentOfCheckout');

        Route::get('/users/{user}/store/create', 'StoreController@adminStoreCreate')->name('admin.store.create');
        Route::get('/store-lists', 'StoreController@index')->name('listOfStores');
        Route::get('/store-lists/{store}/show', 'StoreController@show_store')->name('show.store.admin.panel');
        Route::get('/store-lists/{store}/hide', 'StoreController@hide_store')->name('hide.store');
        Route::get('/store-lists/{store}/approved', 'StoreController@approved_store')->name('approved.store');
        Route::get('/store-lists/{store}/pending', 'StoreController@pending_store')->name('pending.store');
        Route::get('/store-lists/{store}/rejected', 'StoreController@reject_store')->name('rejected.store');
        Route::get('/store-pending-lists', 'StoreController@PendingList')->name('listOfPendingStores');
        Route::get('/province/{province}/city/ajax', 'StoreController@getCityByAjax');
        Route::get('/store-lists/{store}/show-store/ajax', 'StoreController@makeVisible');
        Route::get('/store-lists/{store}/hide-store/ajax', 'StoreController@makeInvisible');
        Route::get('/store-lists/{store}/approved-store-status/ajax', 'StoreController@makeApprovedStatus');
        Route::get('/store-lists/{store}/reject-store-status/ajax', 'StoreController@makeRejectStatus');
        Route::get('/store-lists/{store}/pending-store-status/ajax', 'StoreController@makePendingStatus');
        Route::get('/store-list/shop/{slug}/edit', 'StoreController@edit')->name('editStore');
        Route::post('/store-list/shop/{slug}/update', 'StoreController@update')->name('updateStore');
        Route::get('/store-list/shop/{slug}/plans', 'StoreController@plan')->name('planStore');
        Route::get('/plans-subscription/{subscription}/delete', 'PlanSubscriptionController@delete')->name('plans.subscription.delete');
        Route::post('/store-list/shop/{slug}/plans/set-plan', 'StoreController@setPlan')->name('setPlanStore');
        Route::get('/store-list/shop/{store}/photos', 'StoreController@editPhoto')->name('editPhoto');
        Route::post('/store-list/shop/{store}/photos/update', 'StoreController@updatePhoto')->name('updatePhoto');
        Route::get('/store-list/shop/{store_photo}/photos/delete', 'StoreController@deletePhoto')->name('deletePhoto');

        Route::get('/all-products', 'AllProductSellerController@listAll')->name('allProductSellerList');
        Route::get('/all-services', 'AllProductSellerController@listAll')->name('allServiceSellerList');
        Route::get('/product-lists/{product}/show', 'AllProductSellerController@show_product')->name('show.product');
        Route::get('/product-lists/{product}/hide', 'AllProductSellerController@hide_product')->name('hide.product');
        Route::get('/product-lists/{product}/approved', 'AllProductSellerController@approved_product')->name('approved.product');
        Route::get('/product-lists/{product}/pending', 'AllProductSellerController@pending_product')->name('pending.product');
        Route::get('/product-lists/{product}/rejected', 'AllProductSellerController@reject_product')->name('rejected.product');
        Route::get('/product-lists/{product}/setVip', 'AllProductSellerController@setVip')->name('set.vip.product');
        Route::get('/product-lists/{product}/unSetVip', 'AllProductSellerController@unSetVip')->name('unset.vip.product');

        Route::get('/product-seller-attribute/{store}/{productSeller}', 'ProductSellerAttributeController@index')->name('product.seller.attribute.index');
        Route::post('product-seller-attribute', 'ProductSellerAttributeController@create')->name('product.seller.attribute.create');
        Route::get('product-seller-attribute/{attribute}', 'ProductSellerAttributeController@delete')->name('product.seller.attribute.delete');
        Route::put('product-seller-attribute/{attribute}', 'ProductSellerAttributeController@update')->name('product.seller.attribute.update');

        Route::get('/guilds/product', 'GuildController@list')->name('guildList');
        Route::get('/guilds/service', 'GuildController@serviceList')->name('guildServiceList');
        Route::post('/guilds/create', 'GuildController@create')->name('createGuild');
        Route::put('/guilds/update', 'GuildController@update')->name('updateGuild');
        Route::get('/guilds/{guild}/delete', 'GuildController@delete')->name('deleteGuild');

        Route::get('/attributes/product', 'AttributeController@list')->name('attributeList');
        Route::post('/attributes/create', 'AttributeController@create')->name('createAttribute');
        Route::put('/attributes/update', 'AttributeController@update')->name('updateAttribute');
        Route::get('/attributes/{attribute}/delete', 'AttributeController@delete')->name('deleteAttribute');

        
        Route::get('/guilds/{guild}/category', 'CategoryController@list')->name('categoryOfGuild');
        Route::post('/save-category', 'CategoryController@store')->name('saveCategory');
        Route::get('/category/{category}/delete', 'CategoryController@delete')->name('deleteCategory');
        Route::put('/category/{category}/update', 'CategoryController@update')->name('updateCategory');

        Route::get('/categories/{category}/sub-category', 'SubCategoryController@list')->name('showSubCategories');
        Route::post('/categories/{category}/sub-categories', 'SubCategoryController@save')->name('saveSubCategory');
        Route::get('/sub-categories/{subCategory}/delete', 'SubCategoryController@delete')->name('deleteSubCategory');
        Route::put('/sub-categories/{subCategory}/update', 'SubCategoryController@update')->name('updateSubCategory');
        Route::get('/categories/{category}/sub-categories', 'SubCategoryController@getByCategory')->name('searchSubcatOfProducts');

        Route::get('/store-list/shop/{storeUserNameSlug}', 'ProductSellerController@index')->name('listOfProductSeller');
        Route::get('/store-lists/shop/{store}/product/{product}', 'ProductSellerController@showProduct')->name('showSingleProduct');
        Route::get('/product/{product}', 'ProductSellerController@adminEditPage')->name('admin.product.edit.page');
        Route::put('/product/{product}', 'ProductSellerController@adminUpdate')->name('admin.product.update');
        Route::get('/store-list/shop/{storeId}/product/{product}/photos', 'ProductSellerController@editProductPhotos')->name('editProductPhotos');
        Route::put('/store-list/shop/{store}/product/{product}/photos/update', 'ProductSellerController@updateProductPhotos')->name('updateProductPhotos');
        Route::get('/store-list/shop/{store}/product/{product}/photos/delete', 'ProductSellerController@deleteProductPhotos')->name('deleteProductPhotos');
        Route::get('/get-list-of-product-by-ajax', 'ProductSellerController@getByAjax')->name('getListOfProductSellerByAjax');
        Route::get('/products-list/ajax' , 'ProductSellerController@getProductsByNameViaAjax')->name('getProductsListViaAjax');
        Route::get('/product-seller-comments/list-all', 'ProductSellerCommentController@list')->name('productSellerComments');
        Route::get('/product-seller/{product}/comment', 'ProductSellerCommentController@productComment')->name('productComment');

        Route::get('/reagent-code-page/{user?}', 'ReagentCodeController@index')->name('list.of.reagent.code.user');
        Route::get('/reagent-code-users/{user}', 'ReagentCodeController@userReagented')->name('list.of.user.reagented');
        Route::get('/exciting-design', 'ExcitingDesignController@index')->name('exciting.design.page');
        Route::post('/exciting-design', 'ExcitingDesignController@store')->name('exciting.design.store');
        Route::get('/exciting-design/{exciting_design}', 'ExcitingDesignController@delete')->name('exciting.design.delete');

        Route::get('/users/ajax', 'UsersController@getViaAjax')->name('users.get_via_ajax.in_support_page');
        Route::get('/users/search/by-mobile-and-name' , 'UsersController@searchInUsersByMobileAndName')->name('users.search_my_mobile_and_name.via_ajax');
        Route::get('/user/products/ajax' , 'ProductsController@getUserProductViaAjax')->name('users.products.search.ajax');
        Route::get('/product/wallet/stock/ajax' , 'WalletController@getWalletStockViaAjax')->name('users.wallet.stock.ajax');
        Route::get('/stores/ajax', 'StoreController@getStoresViaAjax')->name('stores.get_via_ajax.in_support_page');
        Route::post('/store/save' , 'StoreController@saveStore')->name('admin.store.save');
        Route::get('/store/{store}/edit' , 'StoreController@editStoreInAdmin')->name('admin.store.edit');
        Route::put('/store/{store}/update' , 'StoreController@updateStoreAdmin')->name('admin.store.update');
        Route::post('/message/send-quick' , 'MessageController@sendQuickMessage')->name('admin.send_quick_message_to_user');
        Route::get('/users/{user}/info', 'UsersController@getUserInfoById')->name('admin.get_user_info_by_id');
        Route::put('/users/{user}/info/update/quick', 'UsersController@quickUpdate')->name('admin.user_info.quick_update');
        Route::get('/product-seller/attributes/{attribute}/destroy', 'ProductSellerAttributeController@removeAttributeViaAjax');
        // transactions
        Route::get('/transactions', 'TransactionsController@index')->name('transactions.index');
        Route::get('/transactions/commisions', 'TransactionsController@commisions')->name('transactions.commisions');
        Route::get('/transactions/product/upgrades' , 'TransactionsController@productUpgrades')->name('transactions.product.upgrade');
        Route::get('/transactions/store/upgrades' , 'TransactionsController@storeUpgrades')->name('transactions.store.upgrade');
        Route::get('/transactions/ads' , 'TransactionsController@ads')->name('transactions.ads');
        Route::get('/transactions/orders' , 'TransactionsController@orders')->name('transactions.orders');

        Route::get('/ads/products/ajax/get', 'ProductSellerController@getProductsForAdsPageViaAjax')->name('ads_products_get_via_ajax');
        Route::get('/ads/stores/ajax/get', 'StoreController@getStoresForAdsPageViaAjax')->name('ads_stores_get_via_ajax');
        Route::get('/users/ajax/ads-page', 'UsersController@getUserWithAjaxForAdsFilterSection')->name('ads_filter.users.ajax');
        // messages
        Route::get('/chats/{user_id}' , 'ChatsController@getChatsForAdmin')->name('admin.chats.index');
        Route::get('/chats/messages/{chat_id}/{user_id}' , 'ChatsController@getMessagesForAdmin')->name('admin.chats.messages');
        Route::delete('/chats/messages/delete/{message_id}' , 'ChatsController@deleteMessageForAdmin')->name('admin.chats.messages.delete');
        Route::delete('/chats/delete' , 'ChatsController@deleteChatsForAdmin')->name('admin.chats.delete');
        Route::get('chats/block/{user_id}' , 'ChatsController@adminBlock')->name('admin.chats.block');
        //Report Chats
        Route::get('chats/reports/get' , 'ReportController@getAdminReportChat')->name('admin.chats.report.index');
        // Discounts
        Route::get('discounts' , 'DiscountController@index')->name('discounts.admin.page');
        Route::post('discounts/create' , 'DiscountController@create')->name('discounts.admin.create');
        Route::post('discounts/update/{id}' , 'DiscountController@update')->name('discounts.admin.update');
        Route::post('discounts/delete/{id}' , 'DiscountController@delete')->name('discounts.admin.delete');
        // UsedDiscounts
        Route::get('discounts/used' , 'DiscountController@getUsedPage')->name('discounts.used');
        // Upgrades
        Route::get('upgrades' , 'UpgradeController@positionsPage')->name('upgrades.admin.index');
        Route::put('upgrades/postitions/update' , 'UpgradeController@positionsUpdate')->name('upgrades.admin.positions.update');
        Route::post('upgrades/product/create' , 'UpgradeController@upgradeProduct')->name('upgrades.admin.product.create');
        Route::post('upgrades/store/create' , 'UpgradeController@upgradeStore')->name('upgrades.admin.store.create');
        // Statistics
        Route::get('statistics' , 'AdminController@statistics')->name('admin.statistics');
        // Market
        Route::get('commisions' , 'StoreController@commissions')->name('admin.commissions');
        Route::post('commissions/add' , 'StoreController@addCommission')->name('admin.commissions.add');
        Route::post('commisions/update/{commission_id}' , 'StoreController@updateCommission')->name('admin.commissions.update');
        Route::get('commisions/delete/{commission_id}' , 'StoreController@deleteCommission')->name('admin.commissions.delete');
        // Admin User Login And Delete
        Route::get('user/login/{user_id}' , 'UserController@loginByAdmin')->name('admin.user.login');
        Route::get('user/delete' , 'UserController@deleteUserPage')->name('deleteUserPage');
        Route::post('user/delete' , 'UserController@deleteUser')->name('deleteUser');
        // Admin Downaload Chat Photo
        Route::get('chats/images/{message_id}' , 'MessageController@downloadImage')->name('admin.chats.image');
        // Admin Batch Delete
        Route::get('chats/batch-delete/show' , 'MessageController@showBatchDelete')->name('admin.messages.batchDelete.show');
        Route::post('chats/batch-delete' , 'MessageController@batchDelete')->name('admin.messages.batchDelete');
        // Admin Application Management
        Route::get('application/management' , 'ApplicationController@managementPage')->name('admin.application');
        Route::post('application/upload' , 'ApplicationController@upload')->name('admin.application.upload');

    });
});

//user panel routes
Route::middleware(['auth:web', 'confirmedMobile:web'])->prefix('my-account')->group(function () {
    Route::post('/product/{product}/rate', 'ProductRateController@store')->name('product_seller.set_rate');
    Route::get('/reagent-code-page/{user?}', 'ReagentCodeController@index')->name('list.of.reagent.code.user1');
    // Route::put('/become-marketer/{user}', 'UsersController@becomeMarketer')->name('become.marketer');
    Route::get('/user-info', 'UsersController@profile')->name('user.profile');
    Route::put('/user-info', 'UsersController@updateProfile')->name('update.profile');
    Route::put('/user-info/change-password', 'UsersController@changePassword')->name('user.change.password');
    Route::get('/address', 'AddressController@userAddress')->name('user.address');
    Route::get('/get-user-address-by-ajax', 'AddressController@getUserAddressByAjax')->name('get.user.address.by.ajax');
    Route::post('/address/create', 'AddressController@create')->name('user.address.create');
    Route::post('/address/createByAjax', 'AddressController@createByAjax')->name('user.address.createByAjax');
    Route::get('/address/delete/{address}', 'AddressController@delete')->name('user.address.delete');
    Route::get('/address/{address}', 'AddressController@edit')->name('user.address.edit');
    Route::put('/address/{address}', 'AddressController@update')->name('user.address.update');
    Route::get('/user-info/get-city-by-ajax', 'CityController@getCityByAjax')->name('userGetCityByAjax');
    Route::get('/stores', 'StoreController@showCreateStorePage')->name('create.store.page');
    Route::get('/stores/service', 'StoreController@showCreateServiceStorePage')->name('create.service_store.page');
    Route::post('/stores', 'StoreController@store')->name('stores.store');
    Route::get('/stores/edit', 'StoreController@editStore')->name('edit.store.page');
    Route::get('/stores/service/edit', 'StoreController@editServiceStore')->name('edit.service_store.page');
    Route::put('/stores/update', 'StoreController@updateStore')->name('update.store.page');
    Route::get('/stores/check-username', 'StoreController@checkUsernameByAjax')->name('stores.check.username');
    Route::get('/store/{store}/photos', 'StorePhotoController@index')->name('index.store.photo')->middleware('can:view,store');
    Route::get('/store-photo/{store_photo}/delete', 'StorePhotoController@delete')->name('delete.store.photo')->middleware('can:delete,store_photo');
    Route::post('/store-photo/upload-photo', 'StorePhotoController@uploadPhoto')->name('upload.store.photo');
    Route::get('/products/create', 'ProductSellerController@userProducts')->name('user.product.create.page');
    Route::get('/services/create', 'ProductSellerController@userServices')->name('user.service.create.page');
    Route::post('/products', 'ProductSellerController@createUserProduct')->name('user.product.create');
    Route::post('/services', 'ProductSellerController@createUserService')->name('user.service.create');
    Route::get('/products/{product}/photo', 'ProductSellerPhotoController@index')->name('user.product.photo')->middleware('can:view,product');
    Route::get('/services/{product}/photo', 'ProductSellerPhotoController@index')->name('user.service.photo')->middleware('can:view,product');
    Route::post('/products/{product}/upload-photo', 'ProductSellerPhotoController@uploadPhoto')->name('user.product.upload.photo');
    Route::post('/services/{product}/upload-photo', 'ProductSellerPhotoController@uploadPhoto')->name('user.service.upload.photo');
    Route::get('/products', 'ProductSellerController@products')->name('user.products');
    Route::get('/services', 'ProductSellerController@services')->name('user.services');
    Route::get('products/{product}/delete', 'ProductSellerController@makeStatusDelete')->name('user.product.delete')->middleware('can:delete,product');
    Route::get('services/{product}/delete', 'ProductSellerController@makeStatusDelete')->name('user.service.delete')->middleware('can:delete,product');
    Route::get('/products/{product}/edit', 'ProductSellerController@edit')->name('user.product.edit')->middleware('can:view,product');
    Route::get('/services/{product}/edit', 'ProductSellerController@editService')->name('user.service.edit')->middleware('can:view,product');
    Route::put('/products/{product}/edit', 'ProductSellerController@update')->name('user.product.update')->middleware('can:view,product');
    Route::put('/services/{product}/edit', 'ProductSellerController@updateService')->name('user.service.update')->middleware('can:view,product');
    Route::get('/products/{product}/attributes', 'ProductSellerAttributeController@userAttributes')->name('user.product.attributes')->middleware('can:view,product');
    Route::get('/services/{product}/attributes', 'ProductSellerAttributeController@userAttributes')->name('user.service.attributes')->middleware('can:view,product');
    Route::get('/product-photo/{photo}/delete', 'ProductSellerPhotoController@delete')->name('user.product.photo.delete')->middleware('can:delete,photo');
    Route::get('/service-photo/{photo}/delete', 'ProductSellerPhotoController@delete')->name('user.service.photo.delete')->middleware('can:delete,photo');
    Route::post('/products-attribute/edit', 'ProductSellerAttributeController@edit')->name('user.product.attributes.edit');
    Route::post('/services-attribute/edit', 'ProductSellerAttributeController@edit')->name('user.service.attributes.edit');
    Route::delete('/products-attribute/{attribute}/delete', 'ProductSellerAttributeController@userDelete')->name('user.product.attributes.delete')->middleware('can:delete,attribute');
    Route::delete('/services-attribute/{attribute}/delete', 'ProductSellerAttributeController@userDelete')->name('user.service.attributes.delete')->middleware('can:delete,attribute');
    Route::post('/products-attribute/{product}/store', 'ProductSellerAttributeController@store')->name('user.product.attributes.store');
    Route::post('/services-attribute/{product}/store', 'ProductSellerAttributeController@store')->name('user.service.attributes.store');
    Route::get('/purchase-invoice', 'BillController@purchaseInvoice')->name('user.purchase.invoice');
    Route::get('/purchase-invoice/{bill}/delete', 'BillController@invoiceDelete')->name('user.purchase.invoice.delete')->middleware('can:delete,bill');
    Route::get('/purchase-invoice/{bill}/delivered', 'BillController@makeInvoiceDelivered')->name('user.purchase.invoice.make.delivered')->middleware('can:makeDelivered,bill');
    Route::get('/purchase-invoice/{bill}/invoice-item', 'BillItemController@userInvoiceBillItem')->name('user.purchase.invoice.bill.item')->middleware('can:view,bill');
    Route::get('/sales-invoice/{bill}/sales-item', 'BillItemController@userSalesBillItem')->name('user.purchase.sales.bill.item')->middleware('can:sellView,bill');
    Route::get('/sales-invoice', 'BillController@salesInvoice')->name('user.purchase.sales');
    Route::get('/sales-invoice/{bill}/delete', 'BillController@salesDelete')->name('user.sales.invoice.delete')->middleware('can:sellDelete,bill');
    Route::get('/sales-invoice/{bill}/delivered', 'BillController@makeSalesDelivered')->name('user.sales.invoice.make.delivered')->middleware('can:makeDelivered,bill');
    Route::get('/comments', 'ProductSellerCommentController@userComments')->name('user.comments');
    Route::post('/comments/approve', 'ProductSellerCommentController@makeApprove')->name('user.comments.make.approved');
    Route::post('/comments/reject', 'ProductSellerCommentController@makeReject')->name('user.comments.make.reject');
    Route::post('/comments/respond', 'ProductSellerCommentController@respond')->name('user.comments.respond');
    Route::get('/buy-plan', 'PlanController@userCreatePage')->name('user.plan.create.page');
    Route::any('/buy-store-plan', 'PlanController@userCreate')->name('user.plan.create');
    Route::post('/verify-plan', 'PlanController@verifyPlan')->name('verify.plan');
    Route::get('/plans', 'PlanController@userPlans')->name('user.plans');
    Route::get('/cart', 'CartController@userCarts')->name('user.carts');
    Route::post('/cart', 'BillController@store')->name('cart.store');
    Route::get('/cart/{cart}/increase', 'CartController@increaseQuantity')->name('increase.cart.quantity')->middleware('can:increaseOrDecrease,cart');
    Route::get('/cart/{cart}/decrease', 'CartController@decreaseQuantity')->name('decrease.cart.quantity')->middleware('can:increaseOrDecrease,cart');
    Route::delete('/cart/{cart}', 'CartController@delete')->name('delete.cart')->middleware('can:delete,cart');
    Route::any('/verify-cart', 'BillController@verifyCart')->name('verify.bill');
    Route::get('/favorite-product', 'ProductSellerController@userFavorite')->name('user.favorite.product');
    Route::get('/accounting-document', 'AccountingDocumentsController@userAccountingDocument')->name('user.accounting.document');
    Route::get('/checkoutRequest', 'CheckoutRequestsController@store')->name('user.checkoutRequest');
    Route::post('/store/create-shaba-code', 'StoreController@storeUserShabaCode')->name('create.user.shaba.code');
    Route::post('/report', 'ReportController@store')->name('report.store');
    Route::post('/rate', 'StoreController@setRate')->name('set.rate.store');
    Route::get('/wallet', 'ReagentCodeController@userWallet')->name('panel.wallet.index');
    Route::get('/user-messages', 'MessageController@userMessage')->name('user.messages');
    Route::post('/messages', 'MessageController@userStore')->name('user.store.message');
    Route::post('/wallet', 'WalletController@userCharge')->name('charge.user.wallet');
    Route::any('/wallet-verify', 'WalletController@verifyWallet')->name('verify.wallet');
    Route::any('/payment-verify', 'AdsController@verifyOnlinepay')->name('verify.onlinepay');
    // Route::get('/marketer/reagented-code-users', 'ReagentCodeController@marketerReagentCode')->name('marketer.reagented.code.users');
    // Route::post('/marketer/{user}/store-shaba-code', 'MarketerController@storeShabaCode')->name('store.shaba.code.marketer');
    Route::get('/ads-panel', 'AdsController@createInMyAccount')->name('my_account.ads_panel');
    Route::post('ads-panel', 'AdsController@storeInMyAccount')->name('my_account.ads.save');
    Route::post('/ads/{ads}/extend', 'AdsController@extendsAd')->name('extend.ad');
    Route::post('/ads/{ads}/update', 'AdsController@updateAdInMyAccount')->name('my_account.ads.update');
    Route::post('/ads/{ads}/delete', 'AdsController@deleteAdInMyAccount')->name('my_account.ads.delete');
    Route::put('/ads/{ads}/manually-pay', 'AdsController@payAdsCostManuallyWithWallet')->name('my_account.ads_manually_pay.with_wallet');
    // Chats
    Route::post('chats/create' , 'ChatsController@createChat')->name('chats.create');
    Route::get('chats/get' , 'ChatsController@getChats')->name('chats.get');
    Route::get('chats/rules' , 'ChatsController@getRules')->name('chats.rules.get');
    // Discount
    Route::get('discounts' , 'DiscountController@getDiscountsPage')->name('discounts.user.index');
    Route::post('discounts/store/create' , 'DiscountController@createUserStoreDiscount')->name('discounts.user.store.create');
    Route::post('discounts/product-seller/create' , 'DiscountController@createUserProductDiscount')->name('discounts.user.product.create');
    // Share
    Route::get('share','UserController@sharePage')->name('share.index');
    // Upgrades
    Route::post('upgrades/product/create', 'UpgradeController@upgradeProductUser')->name('upgrades.product.create');
    Route::post('upgrades/store/create', 'UpgradeController@upgradeStoreUser')->name('upgrades.store.create');
    Route::any('upgrade/payment-verify', 'UpgradeController@verifyUpgradePay')->name('verify.upgradepay');
    // Market Routes
    Route::get('market/create' , 'StoreController@showCreateMarketStorePage')->name('create.market.page');
    Route::get('market/edit' , 'StoreController@editMarketStore')->name('edit.market.page');
    Route::get('market/products' , 'StoreController@marketProducts')->name('market.products');
    Route::get('market/bill-items' , 'StoreController@marketBillItems')->name('market.bill-items');
    Route::get('market/add-product/{product_id}' , 'StoreController@addProductToMarket')->name('market.products.add');
    Route::get('market/delete-product/{product_id}' , 'StoreController@deleteProductFromMarket')->name('market.products.delete');
    Route::get('market/marketers' , 'StoreController@getMyMarketers')->name('marketers.page');
    // Downaload Chat Photo
    Route::get('chats/images/{message_id}', 'MessageController@userDownloadImage')->name('user.chats.image');

    // Upload Chat Photo
    Route::post('/upload/temp' , 'ChatsController@uploadTempFile')->name('upload.temp');
    Route::delete('/upload/temp' , 'ChatsController@deleteTempFile')->name('delete.temp');

    // Users
    Route::get('users/{user_id}' , 'UserController@getUserInAdmin')->name('admin.user.get');

});

//unprotected routes
Route::prefix('pay-api')->group(function () {
    Route::get('/pay/request', 'API\GatewayApi@pay');
    Route::any('callback/from/bank', 'API\GatewayApi@callback')->name('cart.payment.callback.app');
    Route::get('pay/successful', 'API\GatewayApi@cartPaymentSuccessful')->name('cart.payment.successful.app');
});
Route::get('/send-sms', 'SmsController@send');
Route::get('/product-list/{productSlug}', 'ProductsController@showSinglePage')->name('singlePage');
Route::get('/list', 'ProductsController@search')->name('listPage');
Route::get('/stores-list', 'StoreController@storesListPage');
Route::get('/products/search', 'ProductsController@autocomplete')->name('searchNamesOfProductsInMaster');
Route::get('/', 'HomePageController@index')->name('mainPage');
Route::get('/product/{product}', 'ProductSellerController@show')->name('show.product.seller');
Route::post('/product/comment', 'ProductSellerCommentController@store')->name('comment.store');
Route::post('/product/{product}/add-cart', 'CartController@store')->name('cart.create');
Route::post('product/add-favorite', 'ProductSellerController@toggleFavorite')->name('toggle.favorite.product');
Route::get('/store/{slug}', 'StoreController@showStore')->name('show.store');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout.user');
Route::get('/province/{province}', 'ProvinceController@setCookie')->name('setProvinceCookie');
Route::get('/search-list', 'ProductSellerController@productsList')->name('products.list');
Route::view('/about', 'frontend.about.index')->name('showAboutPage');
Route::view('/complaints-and-criticisms', 'frontend.complaintsAndCriticisms.index')->name('showComplaintsAndCriticismsPage');
Route::view('/terms-and-conditions', 'frontend.terms_and_conditions.index')->name('showTermsAndConditionPage');
Route::view('/refer-back-products', 'frontend.reffer-back-products.index')->name('reffer_back_products.index');
Route::view('/contact-us', 'frontend.contactUs.index')->name('showContactUsPage');
Route::get('/list-stores', 'StoreController@listStores')->name('list.stores');
Route::get('/autocomplete-search', 'HomePageController@autoCompleteSearch')->name('auto.complete.search');
Route::get('/setting', 'SettingController@index');
Route::post('/setting', 'SettingController@store');
Route::post('/contact-us-mail', 'ContactUsController@sendContactUsMail')->name('contact.us.mail');
Route::post('/complaints-and-criticisms-mail', 'ComplaintsAndCriticismsController@sendComplaintsAndCriticismsMail')->name('complaints.and.criticisms.mail');
Route::get('/guid', 'ContactUsController@guidance')->name('user.guid');
Route::get('/refs', 'ReagentCodeController@refer')->name('web.refer');
// Route::post('/refs', 'ReagentCodeController@saveScore')->name('web.save_score');
Route::get('/refs/download-links', 'ReagentCodeController@showDownloadLinks')->name('web.show_download_links');
Route::get('/api/guid', 'UsersController@showGuidInApp');
Route::middleware('tokenparams')->get('chats/images/app/{message_id}', 'MessageController@userDownloadImage')->name('general.chats.image');

// API ?????
Route::prefix('api')->group(function () {
    //plans
    Route::get('/plans/buy/gateway/init', 'API\PlanSubApi@buyPlanPaymentGatewayInit')->name('buy_plan.payment_gateway_init');
    Route::any('/plans/buy/gateway/callback', 'API\PlanSubApi@buyPlanPaymentGatewayCallback')->name('buy_plan.payment_gateway_callback');
    Route::get('/plans/buy/gateway/finalize', 'API\PlanSubApi@buyPlanPaymentGatewayFinalize')->name('buy_plan.payment_gateway_finalize');
    //bills
    Route::get('/bills/buy/gateway/init', 'API\BillApi@buyBillPaymentGatewayInit')->name('buy_bill.payment_gateway_init');
    Route::any('/bills/buy/gateway/callback', 'API\BillApi@buyBillPaymentGatewayCallback')->name('buy_bill.payment_gateway_callback');
    Route::any('/plans/buy/gateway/finalize', 'API\BillApi@buyBillPaymentGatewayFinalize')->name('buy_bill.payment_gateway_finalize');
    //ads
    Route::get('/ads/buy/gateway/init/{ad_id}', 'ApiV2\AdsApi@PaymentGatewayInit')->name('ads.payment_gateway_init');
    Route::any('/ads/buy/gateway/callback', 'ApiV2\AdsApi@PaymentGatewayCallback')->name('ads.payment_gateway_callback');
    Route::any('/ads/buy/gateway/finalize', 'ApiV2\AdsApi@PaymentGatewayFinalize')->name('ads.payment_gateway_finalize');
    //upgrades
    Route::get('/upgrades/buy/gateway/init', 'ApiV2\UpgradeApi@buyUpgradePaymentGatewayInit')->name('buy_upgrade.payment_gateway_init');
    Route::any('/upgrades/buy/gateway/callback', 'ApiV2\UpgradeApi@buyUpgradePaymentGatewayCallback')->name('buy_upgrade.payment_gateway_callback');
    Route::get('/upgrades/buy/gateway/finalize', 'ApiV2\UpgradeApi@buyUpgradePaymentGatewayFinalize')->name('buy_upgrade.payment_gateway_finalize');

    Route::get('/wallet/user-charge', 'API\WalletApi@userCharge')->name('user.charge.wallet');
    Route::any('/wallet/callback-user-charge', 'API\WalletApi@callbackChargeWallet')->name('user.charge.wallet.callback');
    Route::get('/wallet/finalize-user-charge', 'API\WalletApi@userChargeWalletFinalize')->name('user.charge.wallet.finalize');
    Route::post('/users/address/save', 'AddressController@adminAddressSave')->name('admin.address.save_in_admin_panel')->middleware('auth:admin');
    Route::get('/store/username/check-duplication', 'StoreController@checkUserNameDuplication')->name('check_username_duplication');
});

Route::get('/referers/assign/code' , function(){
    $generator = new App\Helpers\ReagentCodeGenerator();
    $users = App\User::all();
    DB::beginTransaction();
    foreach($users as $user){
        $user->reagent_code = $generator->generate();
        $user->save();
    }
    DB::commit();
    return 'ok';
});

//Route::middleware('guest:web')->group(function(){
//    Route::get('/login' , 'Auth\LoginController@showLoginForm')->name('showLoginForm');
//    Route::post('/login' , 'Auth\LoginController@login')->name('userLogin');
//    Route::post('/register' , 'Auth\RegisterController@register')->name('userRegister');
//    Route::post('/forget-password' , 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('sendResetLinkEmail');
//    Route::get('/forget-password-send-token' , 'Auth\ForgotPasswordController@forgetPasswordSendToken')->name('send.token.form.show');
//    Route::post('/reset-password-send-token' , 'Auth\ResetPasswordController@checkToken')->name('reset.password.send-token');
//    Route::get('/reset-password-form' , 'Auth\ResetPasswordController@showResetForm')->name('reset.password.form.show');
//    Route::post('/reset-password' , 'Auth\ResetPasswordController@resetPassword')->name('resetPassword');
//});
