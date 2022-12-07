<?php


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

use App\Helpers\LaravelCafebazaar\LaravelCafebazaar;

Route::get('cafebazzar/callbak', function (Illuminate\Http\Request $request) {
    return LaravelCafebazaar::handleRedirect($request);
});
Route::middleware('acceptjson')->group(function() {

    Route::post('/users' , 'API\UserApi@register');
    Route::post('/users/verify-mobile' , 'API\UserApi@verifyMobile');
    Route::put('/users/verify-code/resend' , 'API\UserApi@resendVerifyCode');
    Route::post('/users/login' , 'API\UserApi@login');
    Route::get('/product/show-product' , 'API\ProductsApi@showById');
    Route::get('/products/comment' , 'API\ProductsApi@comment');
    Route::get('/products/commentCount' , 'API\ProductsApi@commentCount');
    Route::get('/province' , 'API\ProvinceApi@show');
    Route::get('/city' , 'API\CityApi@show');
    Route::get('/product/search' , 'API\ProductsApi@search');

    Route::get('/main-page' , 'API\SliderApi@show');
    Route::get('/products' , 'API\ProductsApi@showAll');
    Route::get('/products-by-category' , 'API\ProductsApi@showAllByCategory');
    Route::post('/products/similar/favorites' , 'API\ProductsApi@similarProduct');
    Route::get('/products/{product_id}/others-seen', 'API\ProductSellerApi@othersSeenProducts');
    Route::get('/products/{product_id}/suggestions', 'API\ProductSellerApi@suggestionProducts');
    Route::get('/category/get-all' , 'API\CategoryApi@getAllCat');
    Route::get('/SubCategory/get-all' , 'API\SubCategoryApi@getAllSubcategory');

    Route::put('/send-reset-link' , 'API\ForgetPassApi@sendResetLinkMobile');
    Route::put('/change-password' , 'API\ForgetPassApi@changePassword');
    Route::get('/filter-store' , 'API\StoreApi@filterStore');
    Route::get('/productSeller/filter' , 'API\ProductSellerApi@filterProducts');
    Route::get('/productSeller/filter/paginate' , 'API\ProductSellerApi@filterProductsPaginated');
    Route::get('/productSeller/main-page' , 'API\ProductSellerApi@all');
    Route::get('/store/{store}/high-rate' , 'API\ProductSellerApi@highRateByStoreId');
    Route::get('/product-seller/stores/high-rate' , 'API\ProductSellerApi@highRatedStores');
    Route::get('/product-seller/stores/latest' , 'API\ProductSellerApi@latestStores');
    Route::get('/productSeller/filter-main-page' , 'API\ProductSellerApi@filterMainPage');
    Route::get('/store/all' , 'API\StoreApi@allStores');
    Route::get('/store/all/simple' , 'API\StoreApi@allStoresSimple');
    Route::get('guilds' , 'API\GuildApi@index');
    Route::get('guild-categories' , 'API\GuildApi@getCategories');
    Route::get('productSeller/details' , 'API\ProductSellerApi@productDetails');
    Route::get('productSeller/comments' , 'API\ProductSellerCommentApi@productComment');
    Route::get('/store/product-seller' , 'API\ProductSellerApi@storeProducts');
    Route::get('/store/product-seller/paginated' , 'API\ProductSellerApi@storeProductsPaginated');
    Route::post('/user/change-mobile' , 'API\UserApi@changeMobile');
    Route::get('/ads-positions' , 'ApiV2\AdsPositionApi@index');
    Route::get('/product/rate', 'API\ProductSellerRateApi@show')->name('product_seller.get_rate');
    Route::get('/ads' , 'ApiV2\AdsApi@getHomePageAds');
    Route::get('store/get/{id}' , 'API\StoreApi@getSingle');
    // Sliders
    Route::get('sliders', 'API\SliderApi@getSliders')->name('sliders.get');
    // Discounts
    Route::get('homepage/discounts' , 'ApiV2\DiscountApi@getHomepageDiscounts');
    Route::middleware(['auth:api'])->group(function(){
        Route::get('/user/comments' , 'API\ProductSellerCommentApi@userComments')->name('users.all_comments');
        Route::get('/force-logout/banned-users' , 'API\UserApi@forceLogoutBannedUsers');
        Route::post('comments' , 'API\ProductSellerCommentApi@store');
        Route::post('/plans/buy' , 'API\PlanSubApi@buyNewPlan');
        Route::post('/products/add-to-favorites' , 'API\ProductsApi@addToFav');
        Route::post('/users/show-bills' , 'API\BillApi@show');
        Route::get('/users/show-favorite' , 'API\UserApi@showFav');
        Route::post('/bill/save' , 'API\BillApi@save');
        Route::get('/bill/show' , 'API\BillApi@show');
        Route::post('/comment/create' , 'API\ProductSellerCommentApi@create');
        Route::post('/user/edit' , 'API\UserApi@edit');
        Route::get('address' , 'API\AddressApi@index');
        Route::post('address' , 'API\AddressApi@store');
        Route::put('address' , 'API\AddressApi@update');
        Route::delete('address' , 'API\AddressApi@delete');
        Route::get('productSeller' , 'API\ProductSellerApi@index');
        Route::get('services', 'API\ProductSellerApi@serviceIndex');
        Route::get('/productSeller/without-pagination' , 'API\ProductSellerApi@indexWithoutPagination');
        Route::get('/services/without-pagination', 'API\ProductSellerApi@serviceIndexWithoutPagination');
        Route::post('productSeller' , 'API\ProductSellerApi@store');
        Route::post('/v2/productSeller' , 'ApiV2\ProductSellerApi@store');
        Route::post('productSellerEdit' , 'API\ProductSellerApi@update');
        Route::post('/v2/productSellerEdit' , 'ApiV2\ProductSellerApi@update');
        Route::delete('productSeller' , 'API\ProductSellerApi@delete');
        Route::post('productSeller/toggle-fav' , 'API\ProductSellerApi@toggleFavProduct');
        Route::get('/product-comments', 'API\ProductSellerCommentApi@productComments');
        Route::put('/product-comments', 'API\ProductSellerCommentApi@changeStatus');
        Route::get('/store/check-username' , 'API\StoreApi@checkUsername');
        Route::post('/product-store' , 'API\StoreApi@store')->name('storeProductStoreApi');
        Route::post('/service-store', 'API\StoreApi@store')->name('storeServiceStoreApi');
        Route::post('/market-store', 'API\StoreApi@store')->name('storeMarketStoreApi');
        Route::put('/store/increase-hits' , 'API\StoreApi@increaseHits');
        Route::get('/attributes' , 'API\AttributeApi@index');
        Route::get('/planSub-interval-days' , 'API\PlanSubApi@dayInterval');
        Route::get('/bill/sell-bill' , 'API\BillApi@sellBill');
        Route::get('/bill/buy-bill' , 'API\BillApi@buyBill');
        Route::get('/bill/seller-store' , 'API\BillApi@billSellerStore');
        Route::put('/bill/change-status' , 'API\BillApi@changeStatus');
        Route::put('/bill/change-status-with-admin' , 'API\BillApi@changeStatusWithAdmin');
        Route::get('/plans' , 'API\PlanApi@index');
        Route::post('user/change-password' , 'API\UserApi@changePassword');
        Route::post('user/profile-photo' , 'API\UserApi@profilePhoto');
        Route::post('/pay/show-error' , 'API\ErrorTrackingApi@showErro');
        Route::get('/productSeller/favorite' , 'API\ProductSellerApi@favProduct');
        Route::get('/cart' , 'API\CartApi@index');
        Route::get('/cart/plus' , 'API\CartApi@indexPlus');
        Route::post('/cart' , 'API\CartApi@store');
        Route::post('/cart/decrease' , 'API\CartApi@decrease');
        Route::post('/bill/store', 'API\BillApi@store');
        Route::get('/accounting-document' , 'API\AccountingDocumentApi@index');
        Route::put('/store/update-shaba' , 'API\StoreApi@updateShabaCode');
        Route::post('/report' , 'API\ReportApi@store');
        Route::post('/store/rate' , 'API\ProductSellerApi@addRate');
        Route::get('/referred-users' , 'API\WalletApi@userWallet');
        Route::get('/user-messages' , 'API\MessageApi@userMessages');
        Route::post('/user/store-message' , 'API\MessageApi@userStore');
        Route::delete('/message' , 'API\MessageApi@delete');
        Route::get('/user/message-count' , 'API\MessageApi@userMessageCount');
        Route::get('/user-reagent' , 'API\ReagentCodeApi@userReagentCode');
        Route::get('/user/shaba-code' , 'API\UserApi@userShabaCode');
        Route::post('/user/shaba-code' , 'API\UserApi@editShabCode');
        Route::post('/become-marketer' , 'API\UserApi@becomeMarketer');
        Route::get('/users/plans/subscriptions' , 'API\PlanSubApi@getPlanSubscriptionListOfUsers');
        Route::post('/plans/in-app-purchase/buy' , 'API\PlanSubApi@savePlanForUser')->name('api.bazar.in_app_purchase.save');
        Route::post('/ads/in-app-purchase/buy' , 'ApiV2\AdsApi@saveAdForUser')->name('api.ads.bazar.in_app_purchase.save');
        Route::post('/ads' , 'ApiV2\AdsApi@store')->name('ads.store');
        Route::get('/user/ads' , 'ApiV2\AdsApi@userAds');
        Route::delete('/ads/{ads}' , 'ApiV2\AdsApi@destroy')->name('ads.destroy');
        Route::put('/product/rate' , 'API\ProductSellerRateApi@rate')->name('product_seller.rate.update');
        Route::post('/comment/{comment}/respond' , 'API\ProductSellerCommentApi@respond')->name('respond_to_comment');
        Route::post('/store/plan/{plan}/via-wallet' , 'API\PlanSubApi@InAppPurchaseViaWallet');
        // chats
        Route::post('chats/create' , 'ApiV2\ChatsApi@createChat');
        Route::get('chats/get' , 'ApiV2\ChatsApi@getChats');
        Route::post('chats/message/send/{chat_id}', 'ChatsController@sendMessage')->name('chats.message.send');
        Route::get('chats/message/get/{chat_id}', 'ChatsController@getMessages')->name('chats.message.get');
        Route::get('chats/message/get/paginated/{chat_id}', 'ChatsController@getMessagesPaginated')->name('chats.message.get.paginated');
        Route::delete('chats/message/delete/{id}' , 'ChatsController@deleteMessage')->name('chats.message.delete');
        Route::patch('chats/block/{chat_id}' , 'ChatsController@block')->name('chats.block');
        Route::delete('chats/delete/{chat_id}', 'ChatsController@deleteChat')->name('chats.delete');
        Route::get('chats/rules' , 'ApiV2\ChatsApi@getRules');
        Route::delete('chats/message/group-delete' , 'ApiV2\ChatsApi@groupDeleteMessages')->name('chats.message.delete.group');
        Route::delete('chats/group-delete' , 'ApiV2\ChatsApi@groupDeleteChats')->name('chats.delete.group');
        Route::get('chats/store/get/{chat_id}' , 'ApiV2\ChatsApi@getChatStores')->name('chats.store.get');
        Route::get('chats/product/get/{chat_id}' , 'ApiV2\ChatsApi@getChatProducts')->name('chats.product.get');
        Route::get('chats/single-product/get' , 'ApiV2\ChatsApi@getSingleProduct')->name('chats.product.single.get');
        //For Support
        Route::get('chats/support/get' , 'ChatsController@getAdminMessages')->name('chats.support.get');
        Route::post('chats/support/send' , 'ChatsController@sendMessageToAdmin')->name('chats.support.send');
        Route::delete('chats/support/delete/{message}' , 'ChatsController@deleteAdminMessage')->name('chats.support.delete');
        //Chat Reports
        Route::post('chats/report' , 'ReportController@storeReportChat')->name('chats.reports.create');
        //Discounts
        Route::get('discounts/discountables/get/{type}' , 'DiscountController@getDiscountables')->name('discounts.discountables');
        Route::get('discount/validate' , 'DiscountController@validateDiscount')->name('discounts.validate');
        Route::get('discounts/get/all' , 'ApiV2\DiscountApi@getAvailableDiscounts')->name('discounts.get.available');
        Route::delete('user/discounts/delete/{discount_id}' , 'ApiV2\DiscountApi@deleteDiscount');
        Route::post('discounts/store/create', 'ApiV2\DiscountApi@createStoreDiscount')->name('api.discounts.user.store.create');
        Route::post('discounts/product-seller/create', 'ApiV2\DiscountApi@createProductDiscount')->name('api.discounts.user.product.create');
        Route::get('store/discounts/{store_id}' , 'ApiV2\DiscountApi@getStoreDiscounts')->name('api.discounts.store.get');
        Route::get('product_seller/discounts/{product_id}' , 'ApiV2\DiscountApi@getProductDiscounts')->name('api.discounts.product.get');
        // Upgrades
        Route::get('upgrades/positions' , 'ApiV2\UpgradeApi@positions')->name('upgrades.positions');
        Route::get('upgrades/history' , 'ApiV2\UpgradeApi@getUpgradeHistory')->name('upgrades.history');
        Route::post('upgrades/product-service' , 'ApiV2\UpgradeApi@upgradeProduct')->name('upgrades.product');
        Route::post('upgrades/store' , 'ApiV2\UpgradeApi@upgradeStore')->name('upgrades.store');
        // Share
        Route::get('share' , 'ApiV2\UserApi@share')->name('share');
        // Marketing
        Route::get('market/products', 'API\StoreApi@marketProducts')->name('api.market.products');
        Route::post('market/add-product', 'API\StoreApi@addProductToMarket')->name('api.market.products.add');
        Route::delete('market/delete-product', 'API\StoreApi@deleteProductFromMarket')->name('api.market.products.delete');
        Route::get('market/get-mine' , 'API\StoreApi@getMyMarket')->name('market.mine.get');
        Route::get('store/{store_id}/marketers' , 'API\StoreApi@getStoreMarketers')->name('store.marketers.get');
        // Upload Chat Photo
        Route::post('/upload/temp', 'ChatsController@uploadTempFile')->name('upload.temp');
        Route::delete('/upload/temp', 'ChatsController@deleteTempFile')->name('delete.temp');
        //Checkout Requests
        Route::post('checkout/request' , 'ApiV2\CheckoutRequestsApi@store')->name('api.checkout');
    });
});