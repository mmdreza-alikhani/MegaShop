<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// Admin Routes
// ============================================

Route::prefix('management')
    ->name('admin.')
    ->middleware(['auth', 'hasAnyPermission'])
    ->group(function () {

        Route::get('/', [Admin\HomeController::class, 'mainPage'])->name('panel');

        Route::post('ckeditor/upload', [Admin\CKEditorController::class, 'upload'])
            ->name('ckeditor.upload')
            ->middleware('throttle:60,1');

        Route::middleware('permission:users-index')->group(function () {
            Route::resource('users', Admin\UserController::class)->only(['index', 'store', 'update']);
            Route::resource('permissions', Admin\PermissionController::class)->only(['index', 'store', 'update']);
            Route::resource('roles', Admin\RoleController::class)->only(['index', 'store', 'update']);
        });

        Route::middleware('permission:brands-index')->group(function () {
            Route::resource('brands', Admin\BrandController::class)->only(['index', 'store', 'update', 'destroy']);
        });

        Route::middleware('permission:platforms-index')->group(function () {
            Route::resource('platforms', Admin\PlatformController::class)->only(['index', 'store', 'update', 'destroy']);
        });

        Route::middleware('permission:attributes-index')->group(function () {
            Route::resource('attributes', Admin\AttributeController::class)->only(['index', 'store', 'update', 'destroy']);
        });

        Route::middleware('permission:categories-index')->group(function () {
            Route::resource('categories', Admin\CategoryController::class)->except(['show']);
            Route::get('categories/{category}/attributes', [Admin\CategoryController::class, 'getCategoryAttribute'])
                ->name('categories.attributes');
        });

        Route::middleware('permission:tags-index')->group(function () {
            Route::resource('tags', Admin\TagController::class)->only(['index', 'store', 'update', 'destroy']);
        });

        Route::middleware('permission:products-index')->group(function () {
            Route::resource('products', Admin\ProductController::class);

            Route::prefix('products/{product}')->name('products.')->group(function () {
                Route::get('images/edit', [Admin\ProductImageController::class, 'edit'])->name('images.edit');
                Route::post('images', [Admin\ProductImageController::class, 'add'])->name('images.add');
                Route::delete('images/{image}', [Admin\ProductImageController::class, 'destroy'])->name('images.destroy');
                Route::patch('images/{image}/primary', [Admin\ProductImageController::class, 'setPrimary'])->name('images.primary');

                Route::get('category/edit', [Admin\ProductController::class, 'editCategory'])->name('category.edit');
                Route::put('category', [Admin\ProductController::class, 'updateCategory'])->name('category.update');
            });
        });

        Route::middleware('permission:banners-index')->group(function () {
            Route::resource('banners', Admin\BannersController::class);
        });

        Route::middleware('permission:posts-index')->group(function () {
            Route::resource('posts', Admin\PostController::class);
        });

        Route::middleware('permission:comments-index')->group(function () {
            Route::get('comments', [Admin\CommentController::class, 'index'])->name('comments.index');
            Route::patch('comments/{comment}/toggle', [Admin\CommentController::class, 'toggle'])->name('comments.toggle');
            Route::patch('comments/{comment}/approve', [Admin\CommentController::class, 'approve'])->name('comments.approve');
            Route::patch('comments/{comment}/reject', [Admin\CommentController::class, 'reject'])->name('comments.reject');
            Route::delete('comments/{comment}', [Admin\CommentController::class, 'destroy'])->name('comments.destroy');
        });

        Route::middleware('permission:coupons-index')->group(function () {
            Route::resource('coupons', Admin\CouponController::class)->only(['index', 'store', 'update', 'destroy']);
        });

        Route::middleware('permission:orders-index')->group(function () {
            Route::resource('orders', Admin\OrderController::class)->only(['index', 'show']);
        });
    });

// ============================================
// Home Routes
// ============================================

Route::name('home.')->group(function () {

    Route::get('q/{code}', Home\ShortLinkController::class)
        ->name('shortlink.redirect')
        ->middleware('throttle:100,1');

    Route::get('/', [Home\HomeController::class, 'index'])->name('index');
    Route::get('about-us', [Home\HomeController::class, 'aboutUs'])->name('aboutus');

    Route::post('contact', [Home\HomeController::class, 'contactForm'])
        ->name('contactForm')
        ->middleware('throttle:5,1');

    Route::get('search', [Home\SearchController::class, 'search'])
        ->name('search')
        ->middleware('throttle:60,1');

    Route::prefix('login/{provider}')->group(function () {
        Route::get('/', [Home\AuthController::class, 'redirectToProvider'])
            ->name('redirectToProvider')
            ->middleware('throttle:10,1');

        Route::get('/callback', [Home\AuthController::class, 'handleProviderCallback'])
            ->name('handleProviderCallback')
            ->middleware('throttle:10,1');
    });

    Route::get('reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->middleware(['guest', 'throttle:6,1'])->name('password.reset');

    // ============================================
    // Profile Routes
    // ============================================

    Route::prefix('profile')
        ->name('profile.')
        ->middleware('auth')
        ->group(function () {

            Route::get('/', [Home\ProfileController::class, 'info'])->name('info');

            Route::post('update', [Home\ProfileController::class, 'update'])
                ->name('update')
                ->middleware('throttle:10,1');

            Route::prefix('orders')->name('orders.')->group(function () {
                Route::get('/', [Home\ProfileController::class, 'orders'])->name('index');
                Route::get('{order}', [Home\ProfileController::class, 'showOrder'])->name('show');
            });

            Route::get('wishlist', [Home\ProfileController::class, 'wishlist'])->name('wishlist');
            Route::get('comments', [Home\ProfileController::class, 'comments'])->name('comments');

            Route::resource('addresses', Home\ProfileAddressesController::class)->except(['show']);

            Route::get('reset-password', [Home\ProfileController::class, 'resetPassword'])->name('resetPassword');
            Route::post('reset-password', [Home\ProfileController::class, 'resetPasswordCheck'])
                ->name('resetPasswordCheck')
                ->middleware('throttle:5,1');

            Route::get('verify-email', [Home\ProfileController::class, 'verifyEmail'])
                ->name('verifyEmail')
                ->middleware('throttle:3,1');

            Route::get('logout', [Home\ProfileController::class, 'logout'])->name('logout');
        });

    Route::post('comments/{model}/{id}', [Home\CommentController::class, 'store'])
        ->name('comments.store')
        ->middleware(['auth', 'throttle:10,1']);

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [Home\PostController::class, 'index'])->name('index');
        Route::get('{post:slug}', [Home\PostController::class, 'show'])->name('show');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [Home\ProductController::class, 'index'])->name('index');
        Route::get('{product:slug}', [Home\ProductController::class, 'show'])->name('show');

        Route::middleware(['auth', 'throttle:30,1'])->group(function () {
            Route::post('{product}/wishlist', [Home\WishlistController::class, 'add'])->name('wishlist.add');
            Route::delete('{product}/wishlist', [Home\WishlistController::class, 'remove'])->name('wishlist.remove');
        });
    });

    Route::get('categories/{category:slug}', [Home\CategoryController::class, 'show'])->name('categories.show');
    Route::get('platforms/{platform:slug}', [Home\PlatformController::class, 'show'])->name('platforms.show');

    // ============================================
    // Cart & Checkout
    // ============================================

    Route::middleware('auth')->group(function () {

        // Cart Operations (with throttle)
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [Home\CartController::class, 'index'])->name('index');
            Route::post('add', [Home\CartController::class, 'add'])
                ->name('add')
                ->middleware('throttle:30,1'); // 30 per minute
            Route::put('update', [Home\CartController::class, 'update'])
                ->name('update')
                ->middleware('throttle:30,1');
            Route::delete('items/{itemId}', [Home\CartController::class, 'remove'])->name('remove');
            Route::delete('clear', [Home\CartController::class, 'clear'])->name('clear');

            // Coupon
            Route::post('coupon/check', [Home\CartController::class, 'checkCoupon'])
                ->name('coupon.check')
                ->middleware('throttle:10,1'); // 10 per minute

            // Checkout
            Route::get('checkout', [Home\CartController::class, 'checkout'])->name('checkout');
        });

        // ✅ Payment (with strict throttle)
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::post('/', [Home\PaymentController::class, 'payment'])
                ->name('process')
                ->middleware('throttle:5,1'); // 5 per minute

            Route::get('verify', [Home\PaymentController::class, 'verify'])
                ->name('verify')
                ->middleware('throttle:10,1');
        });
    });
});

// ✅ AJAX Endpoints
Route::get('provinces/{province}/cities', [Home\ProfileAddressesController::class, 'getCitiesByProvince'])
    ->name('provinces.cities')
    ->middleware('throttle:60,1');
