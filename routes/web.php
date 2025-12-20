<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

// Biteship API routes
use App\Http\Controllers\BiteshipController;

// Biteship endpoints for shipping
Route::get('/biteship/test', function() { return response()->json(['test' => 'ok']); });
Route::get('/biteship/couriers', [BiteshipController::class, 'getCouriers']);
Route::post('/biteship/rates', [BiteshipController::class, 'getRates']);
Route::post('/biteship/orders', [BiteshipController::class, 'createOrder']);
Route::get('/biteship/orders/{id}', [BiteshipController::class, 'getOrder']);
Route::get('/biteship/tracking/{id}', [BiteshipController::class, 'getTracking']);
Route::get('/biteship/tracking/{waybill_id}/couriers/{courier_code}', [BiteshipController::class, 'getPublicTracking']);
Route::post('/biteship/orders/{id}/cancel', [BiteshipController::class, 'cancelOrder']);
Route::get('/biteship/cancellation-reasons', [BiteshipController::class, 'getCancellationReasons']);

// Midtrans webhook/notification handler
Route::post('/midtrans/notification', [App\Http\Controllers\PaymentController::class, 'handleNotification'])->name('midtrans.notification');
Route::post('/midtrans/webhook', [App\Http\Controllers\PaymentController::class, 'handleNotification']);

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

Route::get('/', function () {
    return view('home');
});


Route::get('/shop', function () {
    $products = Product::all();
    return view('shop', ['products' => $products]);
});

Route::get('/custom', [App\Http\Controllers\CustomController::class, 'index'])->name('custom');

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});


Route::get('/product/{id}', function ($id) {
    $product = Product::findOrFail($id);
    return view('product', ['product' => $product]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::middleware(['auth'])->group(function () {
            Route::get('/orders', [App\Http\Controllers\PaymentController::class, 'orders'])->name('orders');
        Route::get('/belum-bayar', [App\Http\Controllers\PaymentController::class, 'belumBayar'])->name('belum-bayar');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/account', [App\Http\Controllers\ProfileController::class, 'account'])->name('profile.account');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/delete/{id}', [App\Http\Controllers\CartController::class, 'delete'])->name('cart.delete');
    Route::post('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
    Route::get('/checkout', [App\Http\Controllers\CartController::class, 'showCheckout'])->name('checkout.show');
    Route::match(['get', 'post'], '/pay/{id}', [App\Http\Controllers\PaymentController::class, 'pay'])->name('pay');
    Route::get('/checkout/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('checkout.success');
                Route::get('/checkout/pending', [App\Http\Controllers\PaymentController::class, 'pending'])->name('checkout.pending');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/address', [App\Http\Controllers\ProfileController::class, 'address'])->name('profile.address');
    Route::put('/profile/address', [App\Http\Controllers\ProfileController::class, 'updateAddress'])->name('profile.update-address');
    Route::post('/profile/address', [App\Http\Controllers\ProfileController::class, 'updateAddress'])->name('profile.update-address');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [App\Http\Controllers\AdminDashboardController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [App\Http\Controllers\AdminDashboardController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [App\Http\Controllers\AdminDashboardController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [App\Http\Controllers\AdminDashboardController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{product}', [App\Http\Controllers\AdminDashboardController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\AdminDashboardController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::get('/custom-products', [App\Http\Controllers\AdminDashboardController::class, 'customProducts'])->name('admin.custom-products');
    Route::get('/custom-products/create', [App\Http\Controllers\AdminDashboardController::class, 'createCustomProduct'])->name('admin.custom-products.create');
    Route::post('/custom-products', [App\Http\Controllers\AdminDashboardController::class, 'storeCustomProduct'])->name('admin.custom-products.store');
    Route::get('/custom-products/{customProduct}/edit', [App\Http\Controllers\AdminDashboardController::class, 'editCustomProduct'])->name('admin.custom-products.edit');
    Route::put('/custom-products/{customProduct}', [App\Http\Controllers\AdminDashboardController::class, 'updateCustomProduct'])->name('admin.custom-products.update');
    Route::delete('/custom-products/{customProduct}', [App\Http\Controllers\AdminDashboardController::class, 'destroyCustomProduct'])->name('admin.custom-products.destroy');
    Route::get('/users', [App\Http\Controllers\AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/payments', [App\Http\Controllers\AdminPaymentController::class, 'index'])->name('admin.payments');
    Route::get('/payments/{orderId}', [App\Http\Controllers\AdminPaymentController::class, 'show'])->name('admin.payments.show');
    Route::post('/payments/{orderId}/status', [App\Http\Controllers\AdminPaymentController::class, 'updateStatus'])->name('admin.payments.update-status');
        Route::put('/payments/confirm/{id}', [App\Http\Controllers\AdminDashboardController::class, 'confirmPayment'])->name('admin.payments.confirm');
    Route::get('/profile', [App\Http\Controllers\AdminDashboardController::class, 'profile'])->name('admin.profile');
});
