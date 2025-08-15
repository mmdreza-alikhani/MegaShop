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
use App\Http\Controllers\Home\BrandController as HomeBrandController;
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

Route::prefix('/management/')->name('admin.')->middleware(['permission:manage-general', 'auth.check'])->group(function () {

    Route::get('', [AdminHomeController::class, 'mainPage'])->name('panel');

    Route::resource('users', UserController::class)->middleware(['permission:manage-users']);
    Route::get('usersSearch', [UserController::class, 'search'])->middleware(['permission:manage-users'])->name('users.search');
    Route::resource('permissions', PermissionController::class)->middleware(['permission:manage-users']);
    Route::resource('roles', RoleController::class)->middleware(['permission:manage-users']);
    Route::resource('brands', BrandController::class)->middleware(['permission:manage-products']);
    Route::get('brandsSearch', [BrandController::class, 'search'])->middleware(['permission:manage-products'])->name('brands.search');
    Route::resource('platforms', PlatformController::class)->middleware(['permission:manage-products']);
    Route::resource('attributes', AttributeController::class)->middleware(['permission:manage-products']);
    Route::get('attributesSearch', [AttributeController::class, 'search'])->middleware(['permission:manage-products'])->name('attributes.search');
    Route::resource('categories', CategoryController::class)->middleware(['permission:manage-products']);
    Route::get('categoriesSearch', [CategoryController::class, 'search'])->middleware(['permission:manage-products'])->name('categories.search');
    Route::resource('tags', TagController::class)->middleware(['permission:manage-products']);
    Route::get('tagsSearch', [TagController::class, 'search'])->middleware(['permission:manage-products'])->name('tags.search');
    Route::resource('products', ProductController::class)->middleware(['permission:manage-products']);
    Route::get('productsSearch', [ProductController::class, 'search'])->middleware(['permission:manage-products'])->name('products.search');
    Route::resource('banners', BannersController::class)->middleware(['permission:manage-general']);
    Route::get('bannersSearch', [BannersController::class, 'search'])->middleware(['permission:manage-general'])->name('banners.search');
    Route::resource('posts', PostController::class)->middleware(['permission:manage-products']);
    Route::get('postsSearch', [PostController::class, 'search'])->middleware(['permission:manage-products'])->name('posts.search');
    Route::get('comments/toggle', [CommentController::class, 'toggle'])->middleware(['permission:manage-comments'])->name('comments.toggle');
    Route::resource('comments', CommentController::class)->middleware(['permission:manage-comments']);
    Route::get('commentsSearch', [CommentController::class, 'search'])->middleware(['permission:manage-comments'])->name('comments.search');
    Route::resource('coupons', CouponController::class)->middleware(['permission:manage-orders']);
    Route::get('couponsSearch', [CouponController::class, 'search'])->middleware(['permission:manage-orders'])->name('coupons.search');
    Route::resource('orders', OrderController::class)->middleware(['permission:manage-orders']);
    Route::get('ordersSearch', [OrderController::class, 'search'])->middleware(['permission:manage-orders'])->name('orders.search');

    // Approve Comment
    Route::get('/comments/{comment}/change-status', [CommentController::class, 'changeStatus'])->name('comments.changeStatus')->middleware(['permission:manage-comments']);

    // Get Category Attributes
    Route::get('/get-category-attribute/{category}', [CategoryController::class, 'getCategoryAttribute'])->middleware(['permission:manage-products']);

    // Edit Product Images
    Route::get('/products/{product}/edit-images', [ProductImageController::class, 'edit'])->name('products.images.edit')->middleware(['permission:manage-products']);
    Route::delete('/products/{product}/destroy-images', [ProductImageController::class, 'destroy'])->name('products.images.destroy')->middleware(['permission:manage-products']);
    Route::put('/products/{product}/set-to-primary', [ProductImageController::class, 'set_primary'])->name('products.images.set_primary')->middleware(['permission:manage-products']);
    Route::post('/products/{product}/add-images', [ProductImageController::class, 'add'])->name('products.images.add')->middleware(['permission:manage-products']);

    // Edit Product Category
    Route::get('/products/{product}/edit-category', [ProductController::class, 'edit_category'])->name('products.category.edit')->middleware(['permission:manage-products']);
    Route::put('/products/{product}/update-category', [ProductController::class, 'update_category'])->name('products.category.update')->middleware(['permission:manage-products']);

});

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::prefix('/')->name('home.')->group(function () {

    Route::get('', [HomeController::class, 'index'])->name('index');
    Route::get('search', [HomeController::class, 'globalSearch'])->name('global-search');
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

    Route::get('get_province_cities_list', [HomeProfileAddressesController::class, 'get_province_cities_list']);

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

    Route::get('brands/{brand:slug}', [HomeBrandController::class, 'show_products'])->name('brands.products.show');

//    Route::get('register', [HomeAuthController::class, 'register'])->name('register');
//    Route::get('login', [HomeAuthController::class, 'login'])->name('login');
    Route::get('login/{provider}', [HomeAuthController::class, 'redirectToProvider'])->name('redirectToProvider');
    Route::get('login/{provider}/callback', [HomeAuthController::class, 'handleProviderCallback']);

    // Add And Remove Cart //
    Route::post('add-to-cart', [CartController::class, 'add'])->name('cart.add');
    Route::get('remove-from-cart/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('clear-cart', [CartController::class, 'clear'])->name('cart.clear');
    // End: Add And Remove Cart //

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::put('cart', [CartController::class, 'update'])->name('cart.update');
    Route::post('checkCoupon', [CartController::class, 'checkCoupon'])->name('cart.coupons.check');
    Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('payment', [PaymentController::class, 'payment'])->name('cart.payment');
    Route::get('payment-verification', [PaymentController::class, 'paymentVerification'])->name('cart.payment.verification');
});
