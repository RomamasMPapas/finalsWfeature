<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SocialAuthController;
use App\Models\Products_category;

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
// authenticated user routes
Route::middleware('auth')->group(function(){
    // logout route
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/');
    });
});

// authenticated admin routes
// Route::middleware('git');
// user logout route
Route::get('/admin-logout', function () {
    Session::forget('admin');
    return redirect('/admin');
}); // admin logout route

// paystack payment gateway routes
Route::post('/pay', [PaymentController::class,'redirectToGateway'])->name('pay');
Route::get('/payment/callback', [PaymentController::class,'handleGatewayCallback']);
// products routes
Route::get('/',[ProductController::class,'index']);
Route::get('/products',[ProductController::class,'all_products']);
Route::get('/search',[ProductController::class,'search_products']);
Route::post('/removecart',[ProductController::class,'remove'])->name('remove.cart');
Route::post('/ordernow',[ProductController::class,'order_now']);
Route::get('/cartlist',[ProductController::class,'cartlist']);
// Route::get('/cartlist',[UserController::class,'user_data']);
Route::get('/checkout',[ProductController::class,'checkout']);
// Route::post('/addtocart',[ProductController::class,'cart']);
Route::get('/product/{id}',[ProductController::class,'product']);
Route::get('/manage-products',[ProductController::class,'products_all']);
Route::get('/archived-products',[ProductController::class,'archived_products']);
Route::post('/addtocart',[ProductController::class,'cart'])->name('add.cart');
// login and registration routes
Route::view('/register','register');
Route::view('login','login');
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

Route::get('/forgot-password', function () {
    return view('forgot-password');
});
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);

// Social authentication routes
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// User profile routes
Route::get('/profile', [UserController::class, 'profile'])->middleware('auth');
Route::post('/profile/update', [UserController::class, 'updateProfile'])->middleware('auth')->name('profile.update');
Route::get('/delivery', [ProductController::class, 'delivery'])->middleware('auth');
Route::get('/cancel_order/{id}', [ProductController::class, 'cancel_order'])->middleware('auth');
// admin routes
Route::get('/admin',[AdminController::class,'index']);
Route::post('/admin',[AdminController::class,'login']);
Route::get('/dashboard',[AdminController::class,'admin_details']);
// additional admin/product routes
Route::post('/add-category',[ProductController::class,'add_category'])->name('add.category'); // add product category
Route::get('/category-products/{name}',[ProductController::class,'products_by_category']); // show products by category
Route::get('/manage-categories',[ProductController::class,'show_categories']); // manage product category
Route::post('/add-product',[ProductController::class,'add_products']); // add product
Route::post('/delete-cat',[ProductController::class,'delete_cat'])->name('delete.cat'); // delete category
Route::post('/delete-prd',[ProductController::class,'delete_product'])->name('delete.prd'); // delete product
Route::post('/restore-prd',[ProductController::class,'restore_product'])->name('restore.prd'); // restore archived product
Route::get('/edit-product/{id}',[ProductController::class,'show_product']); // show product according to their id
Route::post('/update-product',[ProductController::class,'update_products']); // update product
Route::get('/customers-orders',[ProductController::class,'show_orders']);  // show orders
Route::post('/update_order',[ProductController::class,'update_order'])->name('order.update'); // update order
?>
