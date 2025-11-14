<?php

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

Route::get('/', function () {
    return view('home');
});

Route::get('/shop', function () {
    $products = [
        [
            'id' => 1,
            'name' => 'Gelang Tridatu',
            'price' => 20000,
            'image' => 'gelang-tridatu.png',
            'sale' => false
        ],
        [
            'id' => 2,
            'name' => 'Kalung Manik',
            'price' => 50000,
            'image' => 'kalung-manik.png',
            'sale' => true
        ],
        [
            'id' => 3,
            'name' => 'Radiant Gemstone',
            'price' => 35000,
            'image' => 'radiant-gemstone.png',
            'sale' => false
        ]
    ];
    return view('shop', ['products' => $products]);
});

Route::get('/shop', function () {
    $products = [
        [
            'id' => 1,
            'name' => 'Gelang Tridatu',
            'price' => 20000,
            'image' => 'gelang-tridatu.png',
            'sale' => false
        ],
        [
            'id' => 2,
            'name' => 'Kalung Manik',
            'price' => 50000,
            'image' => 'kalung-manik.png',
            'sale' => true
        ],
        [
            'id' => 3,
            'name' => 'Radiant Gemstone',
            'price' => 35000,
            'image' => 'radiant-gemstone.png',
            'sale' => false
        ]
    ];
    return view('shop', ['products' => $products]);
});

Route::get('/custom', function () {
    return view('custom');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/product/{id}', function ($id) {
    $products = [
        1 => [
            'id' => 1,
            'name' => 'Gelang Tridatu',
            'price' => 20000,
            'image' => 'gelang-tridatu.png',
            'colors' => ['White-Red', 'Red-Black', 'White-Black'],
            'description' => 'Gelang Tridatu adalah gelang tradisional yang memiliki makna spiritual dengan tiga warna yang melambangkan keseimbangan hidup.'
        ],
        2 => [
            'id' => 2,
            'name' => 'Kalung Manik',
            'price' => 50000,
            'image' => 'kalung-manik.png',
            'colors' => ['Multi Color', 'Blue-White', 'Red-Orange'],
            'description' => 'Kalung manik dengan desain modern dan warna yang menarik, cocok untuk melengkapi gaya sehari-hari Anda.'
        ],
        3 => [
            'id' => 3,
            'name' => 'Radiant Gemstone',
            'price' => 35000,
            'image' => 'radiant-gemstone.png',
            'colors' => ['Black-Red', 'Multi Gem', 'Pearl White'],
            'description' => 'Bracelet dengan gemstone yang memancarkan energi positif dan keindahan natural yang elegan.'
        ]
    ];
    
    $product = $products[$id] ?? null;
    if (!$product) {
        abort(404);
    }
    
    return view('product', ['product' => $product]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/account', [App\Http\Controllers\ProfileController::class, 'account'])->name('profile.account');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/address', [App\Http\Controllers\ProfileController::class, 'address'])->name('profile.address');
    Route::post('/profile/address', [App\Http\Controllers\ProfileController::class, 'updateAddress'])->name('profile.update-address');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [App\Http\Controllers\AdminDashboardController::class, 'products'])->name('admin.products');
    Route::get('/custom-products', [App\Http\Controllers\AdminDashboardController::class, 'customProducts'])->name('admin.custom-products');
    Route::get('/users', [App\Http\Controllers\AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/payments', [App\Http\Controllers\AdminDashboardController::class, 'payments'])->name('admin.payments');
    Route::get('/profile', [App\Http\Controllers\AdminDashboardController::class, 'profile'])->name('admin.profile');
});
