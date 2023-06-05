<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Page\FaqController;
use App\Http\Controllers\Page\HomeController;
use App\Http\Controllers\Page\UserProfileController;
use App\Http\Controllers\Page\EventController;
use App\Http\Controllers\Page\CartController;
use App\Http\Controllers\Ajax\CommentController;
use App\Http\Controllers\Page\TicketController;

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

Route::middleware(['throttle:global'])->group(function () {

    Route::get('/', [HomeController::class, 'show'])->name('home');

    Route::get('/faq', [FaqController::class, 'show'])
        ->name('faq');

    // Route::get('/events', [])
    //     ->name('events');

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::middleware(['guestOrUser'])->group(function () {
            Route::get('/', [CartController::class, 'show'])
                ->name('show');

            Route::post('/', [CartController::class, 'addToCart']);

            Route::delete('/', [CartController::class, 'removeFromCart']);
        });

        Route::post('/purchase-cart', [CartController::class, 'purchaseCart'])
            ->middleware(['auth', 'user', 'verified'])
            ->name('purchase-cart');
    });

    Route::prefix('event')->name('event.')->group(function () {
        Route::middleware(['guestOrUser'])->group(function () {
            Route::post('/likes', [EventController::class, 'like'])
                ->middleware(['guestOrUser'])
                ->name('like');

            Route::post('/follow', [EventController::class, 'follow'])
                ->middleware(['user'])
                ->name('follow');
        });

        Route::get('/comments', [CommentController::class, 'list'])
            ->name('getComments');

        Route::post('/comment', [CommentController::class, 'store'])
            ->middleware(['user'])
            ->name('addComment');

        Route::delete('/comment', [CommentController::class, 'remove'])
            ->middleware(['moderator'])
            ->name('removeComment');

        Route::post('/like-comment', [CommentController::class, 'like'])
            ->middleware(['guestOrUser'])
            ->name('likeComment');

        Route::get('/{idString}', [EventController::class, 'show'])
            ->name('page')
            ->where('idString', '^.*-[1-9][0-9]*$');
    });



    Route::post('/change-password', [UserProfileController::class, 'changePassword'])
        ->middleware(['auth'])
        ->name('change-password');

    Route::get('/ticket/{id}', [TicketController::class, 'show'])
        ->where('id', '^[a-z0-9]{64}$')
        ->middleware(['signed'])
        ->name('ticket');

    Route::prefix('user-profile')->middleware(['auth', 'user', 'verified', 'password.confirm'])->group(function () {
        Route::get('/', [UserProfileController::class, 'dashboard'])
            ->name('user-profile');

        Route::get('/tickets', [UserProfileController::class, 'tickets'])
            ->name('user-tickets');

        Route::get('/contact-form', [UserProfileController::class, 'contactForm'])
            ->name('user-contact-form');

        Route::post('/contact-form', [UserProfileController::class, 'sendContactMail']);
    });


    Route::get('/followed', [UserProfileController::class, 'followed'])
        ->middleware(['auth', 'user'])
        ->name('followed');



    Route::get('/moderator-dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'moderator', 'verified'])->name('moderator-dashboard');


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


        // Route::delete('/{idEvent}/comments/{idComment}', [])->whereNumber('idEvent')->whereNumber('idComment')->middleware(['moderator'])->name('');
    });

    // Route::get('/stats/{type}, [])->middleware(['moderator'])->name()->whereIn('category', ['daily', 'monthly', 'annually']);
    // Route::get('/cities/{search}, [])->middleware(['moderator'])->name();
});
