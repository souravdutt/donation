<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------s-------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/****************************/
/**** ADMIN GUEST START *****/
/****************************/
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/', [UserController::class, 'signin'])->name('signin');
    Route::post('/signin', [UserController::class, 'signinSubmit'])->name('signin-submit');
    // Route::get('/forgotPassword', [UserController::class, 'forgotPassword'])->name('forgotPassword');
    // Route::post('/forgotPassword', [UserController::class, 'forgotPasswordSubmit'])->name('forgotPassword-submit');
    // Route::get('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');
    // Route::post('/reset-password', [UserController::class, 'resetPasswordSubmit'])->name('resetPassword-submit');
});
/****************************/
/***** ADMIN GUEST ENDS *****/
/****************************/



/*****************************/
/***** ADMIN AUTH START ******/
/*****************************/
Route::prefix('admin')->middleware('auth')->name('auth.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    // Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    // Route::post('/profile', [UserController::class, 'profileSubmit'])->name('profile-submit');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('/change-password', [UserController::class, 'changePasswordSubmit'])->name('changePassword-submit');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    # Donation
    Route::get('/donation', [ DonationController::class, 'donation'])->name('donation');
    Route::delete('/donation', [ DonationController::class, 'delete'])->name('donation.delete');
    # Leaderboard
    Route::put('/leaderboard-status', [ DonationController::class, 'leaderBoardStatus'])->name('donation.leaderboard-status');
    # Contact Queries
    Route::get('/queries', [ QueryController::class, 'queries'])->name('queries');
    Route::delete('/queries', [ QueryController::class, 'delete'])->name('querie.delete');
    # Albums
    Route::get('/albums', [ AlbumController::class, 'albums'])->name('albums');
    Route::delete('/albums', [ AlbumController::class, 'deleteAlbums'])->name('albums.delete');
    Route::post('/albums', [ AlbumController::class, 'addAlbums'])->name('albums.add');
    Route::put('/albums', [ AlbumController::class, 'updateAlbums'])->name('albums.update');
    Route::get('/album/detail', [ AlbumController::class, 'albumDetail'])->name('album.detail');
    # Media
    Route::delete('/media', [ AlbumController::class, 'deleteMedia'])->name('media.delete');
    # Team/Trust Members
    Route::get('/members', [ PagesController::class, 'members'])->name('members');
    Route::post('/members', [ PagesController::class, 'addMembers'])->name('members.add');
    Route::put('/members', [ PagesController::class, 'updateMember'])->name('members.update');
    Route::delete('/members', [ PagesController::class, 'deleteMembers'])->name('members.delete');

    # Extra: CKEditor, ...
    Route::post('/upload/media/{type}', [ AlbumController::class, 'uploadCKEditorMedia'])->name('upload.media');
});
/*****************************/
/****** ADMIN AUTH ENDS ******/
/*****************************/



/*****************************/
/**** LANDING PAGES START ****/
/*****************************/
# Landing Pages
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('about', [HomeController::class, 'about'])->name('home.about');
Route::get('leaderboard', [HomeController::class, 'leaderboard'])->name('home.leaderboard');
Route::get('donate', [HomeController::class, 'donate'])->name('home.donate');
Route::get('albums', [HomeController::class, 'albums'])->name('home.albums');
Route::get('album/{id}', [HomeController::class, 'album'])->name('home.album');
Route::get('privacy', [HomeController::class, 'privacy'])->name('home.privacy-policy');
# Contact Form
Route::post('contact', [HomeController::class, 'contactSubmit'])->name('home.contact.submit');
# Checkout
Route::post('process-checkout', [CheckoutController::class, 'createSession'])->name('process.checkout');
Route::get('payment-success', [CheckoutController::class, 'paymentSuccess'])->name('stripe.success');
Route::get('failed-payment',  [CheckoutController::class, 'handleFailedPayment'])->name('stripe.payment');
# Extra: Select2, ...
Route::get('find/countries',  [HomeController::class, 'findCountries'])->name('find.countries');
Route::get('find/states',  [HomeController::class, 'findStates'])->name('find.states');
Route::get('find/cities',  [HomeController::class, 'findCities'])->name('find.cities');
/*****************************/
/**** LANDING PAGES ENDS *****/
/*****************************/
