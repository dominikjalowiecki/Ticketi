<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Page\FaqController;
use App\Http\Controllers\Page\HomeController;
use App\Http\Controllers\Page\UserProfileController;
use App\Http\Controllers\Page\EventController;

// use Illuminate\Support\Facades\DB;

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

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'show'])->name('home');

Route::get('/faq', [FaqController::class, 'show'])
    ->name('faq');

// Route::get('/events', [])
//     ->name('events');

// Route::get('/cart', [])
//     ->name('cart');

Route::prefix('event')->name('event.')->group(function () {
    // Route::post('/{id}/likes', [])->whereNumber('id')->name('');
    // Route::post('/{id}/comments', [])->whereNumber('id')->name('');
    // Route::post('/{idEvent}/comments/{idComment}/likes', [])->whereNumber('idEvent')->whereNumber('idComment')->name('');
    // Route::delete('/{idEvent}/comments/{idComment}', [])->whereNumber('idEvent')->whereNumber('idComment')->middleware(['moderator'])->name('');
    Route::get('/{idString}', [EventController::class, 'show'])
        ->name('event')
        ->where('idString', '^.*-\d+$');
});

Route::prefix('user-profile')->middleware(['auth', 'user', 'verified', 'password.confirm'])->group(function () {
    Route::get('/', [UserProfileController::class, 'show'])
        ->name('user-profile');
    // Route::post('/change-password), [])->name('');
    // Route::get('/tickets', [])->name('');
    // Route::get('/contact', [])->name('');
    // Route::post('/contact', [])->name('');
    // Route::get('/followed', [])->name('');
    // Route::post('/followed', [])->name('');
});

Route::prefix('administration-panel')->name('admin.')->middleware(['auth', 'moderator'])->group(function () {
    // Route::get('/', [])->name('dashboard');
    // Route::get('/orders', [])->name('');
    // Route::get('/add-moderator', [])->name('');
    // Route::post('/add-moderator', [])->middleware(['password.confirm'])->name('');
    // Route::get('/change-password', [])->name('');
    // Route::post('/change-password), [])->name('');
    // Route::get('/create-event', [])->name('');
    // Route::post('/create-event), [])->name('');
    // Route::get('/search-events/{search}', [])->name('');
    // Route::get('/edit-event/{id}', [])->whereNumber('id')->name('');
    // Route::post('/edit-event/{id}), [])->whereNumber('id')->name('');
});

// Route::get('/stats/{type}, [])->middleware(['moderator'])->name()->whereIn('category', ['daily', 'monthly', 'annually']);
// Route::get('/cities/{search}, [])->middleware(['moderator'])->name();
