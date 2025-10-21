<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Home\AuthController as HomeAuthController;
use App\Http\Controllers\Home\CartController;
use App\Http\Controllers\Home\CategoryController as HomeCategoryController;
use App\Http\Controllers\Home\CommentController as HomeCommentController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\PaymentController;
use App\Http\Controllers\Home\PlatformController as HomePlatformController;
use App\Http\Controllers\Home\PostController as HomePostController;
use App\Http\Controllers\Home\ProductController as HomeProductController;
use App\Http\Controllers\Home\ProfileAddressesController as HomeProfileAddressesController;
use App\Http\Controllers\Home\ProfileController as HomeProfileController;
use App\Http\Controllers\Home\SearchController;
use App\Http\Controllers\Home\WishListController as HomeWishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('/management/')->name('admin.')->middleware(['auth.check'])->group(function () {

    Route::get('', [AdminHomeController::class, 'mainPage'])->name('panel');

    Route::resource('users', UserController::class)->middleware(['permission:users-index'])->only(['index', 'store', 'update']);
    Route::resource('permissions', PermissionController::class)->middleware(['permission:users-index'])->only(['index', 'store', 'update']);
    Route::resource('roles', RoleController::class)->middleware(['permission:users-index'])->only(['index', 'store', 'update']);
    Route::resource('brands', BrandController::class)->middleware(['permission:brands-index'])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('platforms', PlatformController::class)->middleware(['permission:platforms-index'])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('attributes', AttributeController::class)->middleware(['permission:attributes-index'])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('categories', CategoryController::class)->middleware(['permission:categories-index'])->except(['show']);
    Route::resource('tags', TagController::class)->middleware(['permission:tags-index'])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('products', ProductController::class)->middleware(['permission:products-index']);
    Route::resource('banners', BannersController::class)->middleware(['permission:banners-index']);
    Route::resource('posts', PostController::class)->middleware(['permission:posts-index']);
    Route::get('comments/toggle', [CommentController::class, 'toggle'])->middleware(['permission:comments-index'])->name('comments.toggle');
    Route::resource('comments', CommentController::class)->middleware(['permission:comments-index'])->only(['index']);
    Route::resource('coupons', CouponController::class)->middleware(['permission:coupons-index'])->only(['index', 'store', 'update', 'destroy']);
    Route::resource('orders', OrderController::class)->middleware(['permission:orders-index'])->only(['index', 'show']);

    // Approve Comment
    Route::get('/comments/{comment}/change-status', [CommentController::class, 'changeStatus'])->name('comments.changeStatus')->middleware(['permission:comments-toggle']);

    // Get Category Attributes
    Route::get('/get-category-attribute/{category}', [CategoryController::class, 'getCategoryAttribute'])->middleware(['permission:categories-index']);

    // Edit Product Images
    Route::get('/products/{product}/edit-images', [ProductImageController::class, 'edit'])->name('products.images.edit')->middleware(['permission:products-edit']);
    Route::delete('/products/{product}/destroy-images', [ProductImageController::class, 'destroy'])->name('products.images.destroy')->middleware(['permission:products-edit']);
    Route::put('/products/{product}/set-to-primary', [ProductImageController::class, 'set_primary'])->name('products.images.set_primary')->middleware(['permission:products-edit']);
    Route::post('/products/{product}/add-images', [ProductImageController::class, 'add'])->name('products.images.add')->middleware(['permission:products-edit']);

    // Edit Product Category
    Route::get('/products/{product}/edit-category', [ProductController::class, 'edit_category'])->name('products.category.edit')->middleware(['permission:products-edit']);
    Route::put('/products/{product}/update-category', [ProductController::class, 'update_category'])->name('products.category.update')->middleware(['permission:products-edit']);

});

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest', 'throttle:6,1')->name('password.reset');

Route::prefix('/')->name('home.')->group(function () {

    Route::get('', [HomeController::class, 'index'])->name('index');
    Route::get('s', [SearchController::class, 'search'])->name('search');
    Route::get('aboutus', [HomeController::class, 'aboutUs'])->name('aboutus');
    Route::get('aboutus#contact', [HomeController::class, 'aboutUs'])->name('aboutus.contact');
    Route::post('contactForm', [HomeController::class, 'contactForm'])->name('contactForm');

    Route::prefix('profile/')->middleware('auth.check')->name('profile.')->group(function () {
        Route::get('', [HomeProfileController::class, 'info'])->name('info');
        Route::get('info', [HomeProfileController::class, 'info'])->name('info');
        Route::post('update', [HomeProfileController::class, 'update'])->name('update');
        Route::get('orders', [HomeProfileController::class, 'orders'])->name('orders');
        Route::get('orders/{order}', [HomeProfileController::class, 'showOrder'])->name('orders.showOrder');
        Route::get('wishlist', [HomeProfileController::class, 'wishlist'])->name('wishlist');
        Route::get('comments', [HomeProfileController::class, 'comments'])->name('comments');
        Route::prefix('addresses/')->name('addresses.')->group(function () {
            Route::get('', [HomeProfileAddressesController::class, 'index'])->name('index');
            Route::get('create', [HomeProfileAddressesController::class, 'create'])->name('create');
            Route::post('store', [HomeProfileAddressesController::class, 'store'])->name('store');
            Route::get('{address}/edit', [HomeProfileAddressesController::class, 'edit'])->name('edit');
            Route::put('{address}/update', [HomeProfileAddressesController::class, 'update'])->name('update');
            Route::delete('{address}/delete', [HomeProfileAddressesController::class, 'destroy'])->name('destroy');
        });
        Route::get('resetPassword', [HomeProfileController::class, 'resetPassword'])->name('resetPassword');
        Route::post('resetPasswordCheck', [HomeProfileController::class, 'resetPasswordCheck'])->name('resetPasswordCheck');
        Route::get('verifyEmail', [HomeProfileController::class, 'verifyEmail'])->name('verifyEmail');
        Route::get('logout', [HomeProfileController::class, 'logout'])->name('logout');
    });

    Route::get('get_province_cities_list/{province}', [HomeProfileAddressesController::class, 'get_province_cities_list']);

    Route::post('comments/{model}/{id}', [HomeCommentController::class, 'store'])->name('comments.store');

    Route::prefix('posts/')->name('posts.')->group(function () {
        Route::get('', [HomePostController::class, 'index'])->name('index');
        Route::get('{post:slug}', [HomePostController::class, 'show'])->name('show');
    });

    Route::prefix('products/')->name('products.')->group(function () {
        Route::get('', [HomeProductController::class, 'index'])->name('index');
        Route::prefix('wishlist/')->middleware('auth.check')->name('wishlist.')->group(function () {
            Route::get('add/{product}', [HomeWishlistController::class, 'add'])->name('add');
            Route::get('remove/{product}', [HomeWishlistController::class, 'remove'])->name('remove');
        });
        Route::get('{product}', [HomeProductController::class, 'show'])->name('show');
    });

    Route::get('categories/{category:slug}', [HomeCategoryController::class, 'show_category'])->name('categories.show');

    Route::get('platforms/{platform:slug}', [HomePlatformController::class, 'show_products'])->name('platforms.products.show');

    Route::get('login/{provider}', [HomeAuthController::class, 'redirectToProvider'])->name('redirectToProvider');
    Route::get('login/{provider}/callback', [HomeAuthController::class, 'handleProviderCallback']);

    // Add And Remove Cart //
    Route::post('add-to-cart', [CartController::class, 'add'])->middleware('auth')->name('cart.add');
    Route::get('remove-from-cart/{itemable_id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('clear-cart', [CartController::class, 'clearCart'])->name('cart.clear');
    // End: Add And Remove Cart //

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::put('cart', [CartController::class, 'update'])->name('cart.update');
    Route::post('checkCoupon', [CartController::class, 'checkCoupon'])->name('cart.coupons.check');
    Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('payment', [PaymentController::class, 'payment'])->name('cart.payment');
    Route::get('payment-verification', [PaymentController::class, 'paymentVerification'])->name('cart.payment.verification');
});
