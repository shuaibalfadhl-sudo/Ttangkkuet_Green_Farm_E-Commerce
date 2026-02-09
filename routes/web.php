<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\WishlistController; 
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;  

// Disable the default login/logout routes from Auth::routes()
Auth::routes([
    'verify' => true,
    'login' => false,
    'logout' => false,
]);
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::get('/email/verified', function () {
    return view('auth.verified-success');
    })->name('verification.success');
Route::get('/email/verify/status', [VerificationController::class, 'checkStatus'])
    ->middleware('auth')
    ->name('verification.status');

// Add custom routes for login and logout
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');
Route::get('/products/{product_slug}/reviews', [ShopController::class, 'product_reviews'])->name('shop.product.reviews');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::put('/cart/qty/update/{rowId}', [CartController::class, 'updateQty'])->name('cart.qty.update');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.empty');

Route::post('/cart/apply-coupon',[CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon',[CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');

Route::post('wishlist/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/item/remove/{rowId}', [WishlistController::class, 'remove_item'])->name('wishlist.item.remove');
Route::put('/wishlist/increase-quantity/{rowId}', [WishlistController::class, 'increase_wishlist_quantity'])->name('wishlist.qty.increase');
Route::put('/wishlist/decrease-quantity/{rowId}', [WishlistController::class, 'decrease_wishlist_quantity'])->name('wishlist.qty.decrease');
Route::delete('/wishlist/clear', [WishlistController::class, 'empty_wishlist'])->name('wishlist.items.clear');
Route::post('wishlist/move-to-cart/{rowId}', [WishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');

Route::get('/checkout',[CartController::class, 'checkout'])->name('cart.checkout')->middleware('verified');
Route::post('/place-an-order',[CartController::class, 'place_an_order'])->name('cart.place.an.order');
Route::get('/order-confirmation',[CartController::class, 'order_confirmation'])->name('cart.order.confirmation');
Route::post('/orders/{id}/upload-receipt', [CartController::class, 'uploadReceipt'])->name('orders.uploadReceipt');

Route::get('/payment/card', [CartController::class, 'renderCardPayment'])->name('cart.payment.card'); 
Route::post('/stripe',[CartController::class, 'stripeCheckout'])->name('stripe.checkout');
Route::post('/orders/{order}/refund', [CartController::class, 'refund'])->name('orders.refund');
Route::put('/orders/{id}/update-status', [CartController::class, 'updateStatus'])->name('orders.updateStatus');



Route::get('cart/pay-success',[CartController::class, 'payment_success'])->name('cart.payment.success');
Route::post('cart/pay-cancel',[CartController::class, 'payment_cancel'])->name('cart.payment.cancel');

Route::get('about',[HomeController::class,'about'])->name('home.about');

Route::get('contact-us',[HomeController::class,'contact'])->name('home.contact');
Route::post('contact/store',[HomeController::class,'contact_store'])->name('home.contact.store');

Route::get('/search', [HomeController::class, 'search'])->name('home.search');

//Policy Pages
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('home.privacy.policy');   
Route::get('/delivery-policy', [HomeController::class, 'deliveryPolicy'])->name('home.delivery.policy');
Route::get('/return-policy', [HomeController::class, 'returnPolicy'])->name('home.return.policy');

Route::get('products/{product_slug}/review',[HomeController::class, 'validates'])->name('validate.review');
Route::post('send-review',[UserController::class, 'store_review'])->name('review.store');
Route::get('/reviews/{review}/edit', [UserController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}/update', [UserController::class, 'update_review'])->name('reviews.update');
Route::delete('/reviews/{review}/delete', [UserController::class, 'delete_review'])->name('reviews.delete');

// show forgot password form
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth','verified'])->group(function(){
    Route::post('/reviews/{review}/like', [UserController::class, 'like'])->name('reviews.like');
    Route::delete('/reviews/{review}/unlike', [UserController::class, 'unlike'])->name('reviews.unlike');

    Route::put('/user/profile/update', [Usercontroller::class, 'updateProfile'])->name('user.update.profile');
    Route::get('/user/password', [Usercontroller::class, 'password'])->name('user.password');
    Route::put('/user/update-password', [UserController::class, 'updatePassword'])->name('user.update.password');
    
    Route::get('/account-dashboard', [Usercontroller::class, 'index'])->name('user.index');
    Route::get('/account-orders', [Usercontroller::class, 'orders'])->name('user.orders');
    Route::get('/account-order/{order_id}/detials', [Usercontroller::class, 'order_details'])->name('user.order.details');
    Route::put('/account-order/cancel-order', [Usercontroller::class, 'order_cancel'])->name('user.order.cancel');

    Route::post('/user/address/store',[Usercontroller::class, 'address_store'])->name('user.address.store');
    Route::get('/user/address', [Usercontroller::class, 'address'])->name('user.address');
    Route::get('/user/address/edit',[Usercontroller::class, 'address_edit'])->name('user.address.edit');
    Route::put('/user/address/update',[Usercontroller::class, 'address_update'])->name('user.address.update');

    Route::get('/user/print/{order_id}', [Usercontroller::class, 'print'])->name('user.print');

    // Show conversation between authenticated user and admin
	Route::get('/user/messages', [MessageController::class, 'userConversation'])->name('user.messages.index');

	// Send a message from user to admin
	Route::post('/user/messages/send', [MessageController::class, 'userSend'])->name('user.message.send');

    // Toggle product update notifications
    Route::post('/user/toggle-updates', [Usercontroller::class, 'toggle'])->name('user.toggleUpdates');
});

Route::middleware(['auth', AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand/add', [AdminController::class, 'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');
    
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/admin/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');

    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/delete/{id}', [AdminController::class, 'product_delete'])->name('admin.product.delete');

    Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add',[AdminController::class,'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store',[AdminController::class,'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit',[AdminController::class,'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update',[AdminController::class,'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

    Route::get('/admin/orders',[AdminController::class,'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details',[AdminController::class,'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status',[AdminController::class, 'update_order_status'])->name('admin.order.status.update');
    Route::put('/admin/orders/mark-shipped', [AdminController::class, 'markAsShipped'])->name('admin.order.shipped');
    Route::put('/admin/orders/mark-delivered', [AdminController::class, 'markAsDelivered'])->name('admin.order.delivered');
    Route::delete('/admin/order/{order_id}/delete', [AdminController::class, 'order_delete'])->name('admin.order.delete');

    Route::get('/admin/slides',[AdminController::class,'slides'])->name('admin.slides');
    Route::get('/admin/slide/add',[AdminController::class,'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/store',[AdminController::class,'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit',[AdminController::class,'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update',[AdminController::class,'slide_update'])->name('admin.slide.update');
    Route::delete('admin/slide/{id}/delete',[AdminController::class,'slide_delete'])->name('admin.slide.delete');

    Route::get('/admin/contacts',[AdminController::class,'contacts'])->name('admin.contacts');
    Route::delete('/admin/contact/{id}/delete',[AdminController::class,'contact_delete'])->name('admin.contact.delete');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/live-search', [AdminController::class, 'liveSearch'])->name('home.live.search');
    Route::get('admin/categories/livesearch', [AdminController::class, 'liveSearchCategory'])->name('admin.category.livesearch');
    Route::get('admin/orders/livesearch', [AdminController::class, 'liveSearchOrder'])->name('admin.order.livesearch');
    //print order
    Route::get('/admin/print/{order_id}', [AdminController::class, 'print'])->name('admin.print');
    Route::get('/admin/download', [AdminController::class, 'downloadMonthlyReport'])->name('admin.download');

    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('admin/users/livesearch', [AdminController::class, 'liveSearchUser'])->name('admin.user.livesearch');

    // Role Management
    Route::get('admin/user/{id}/edit-role', [AdminController::class, 'editUserRole'])->name('admin.user.edit_role');
    Route::put('admin/user/{id}/update-role', [AdminController::class, 'updateUserRole'])->name('admin.user.update_role');
    Route::delete('admin/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    //Setting Page
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings'); 
    Route::post('/admin/advertisement/update', [AdminController::class, 'advertisement_update'])->name('admin.ad.update');
    Route::post('/admin/logo/update', [AdminController::class, 'logoUpdate'])->name('admin.logo.update');
    Route::post('/admin/delivery/update', [AdminController::class, 'deliveryChargeUpdate'])->name('admin.delivery.update');
    Route::post('/admin/contact-info/update', [AdminController::class, 'contactInfoUpdate'])->name('admin.contact.info.update');
    Route::post('/admin/bank/update', [AdminController::class, 'bankInfoUpdate'])->name('admin.bank.update');
    Route::post('/admin/socials/update', [AdminController::class, 'socialLinksUpdate'])->name('admin.socials.update');

    // Admin messaging routes
    Route::get('/admin/messages', [MessageController::class, 'index'])->name('admin.messages.index');
    Route::post('/admin/messages/send', [MessageController::class, 'send'])->name('admin.message.send');
    Route::post('/admin/messages/mark-read', [MessageController::class, 'markRead'])->name('admin.messages.markRead');

    // Live search suggestions for admin messages
    Route::get('admin/messages/live-search', [MessageController::class, 'liveSearch'])->name('admin.messages.live_search');

    // Send mail
    Route::post('/admin/products/send-all-updates', [AdminController::class, 'sendAllUpdates'])->name('admin.products.sendAllUpdates');
});
// Clear application cache
    Broadcast::routes();
 