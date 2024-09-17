<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminController , WorkerAuthController , ClientAuthController,
ClientOrderController, PaymentController, PermissionController, PostController, RoleController, RolePermissionController, WorkerProfileController,
WorkerReviewController};
use App\Http\Controllers\AdminDashbord\AdminNotificationController;
use App\Http\Controllers\AdminDashbord\PostStatusController;

Route::prefix('auth')->group(function () {
    // Admin
    Route::controller(AdminController::class)->prefix('admin')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });

    // Worker
    Route::controller(WorkerAuthController::class)->prefix('worker')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
        Route::get('/verify/{token}', 'verify');
        //Route::get('/reset-password', 'resetPassword');
    });


    // Client
    Route::controller(ClientAuthController::class)->prefix('client')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });
});


Route::get('/unauthorized', function() {
    return response()->json([
        'message' => 'Unauthorized',
    ], 401);
})->name('login');


Route::controller(PostController::class)->prefix('post/worker')->group(function () {
    Route::post('/add-post', 'store')->middleware('auth:worker');
    Route::get('/approved', 'approved');
    Route::get('/show/{id}', 'show');
});

Route::prefix('admin')->group(function () {
    Route::controller(PostStatusController::class)->prefix('post/status')->group(function () {
        Route::post('/change-status', 'changeStatus');
    });
});

// Create Client Order
Route::prefix('client')->group(function () {
    Route::controller(ClientOrderController::class)->prefix('order')->group(function () {
        Route::post('/clients-order', 'addOrder')->middleware('auth:client');
    });

    // Payment
    Route::controller(PaymentController::class)->group(function () {
        Route::post('/pay/{serviceId}', 'processPayment')->middleware('auth:client');
    });
});

// Client Orders for Workers
Route::prefix('worker')->group(function () {
    Route::controller(ClientOrderController::class)->prefix('order')->group(function () {
        Route::get('/allOrders', 'workerOrder')->middleware('auth:worker');
        Route::put('/updateOrder/{id}', 'update')->middleware('auth:worker');
        Route::get('/showOrder/{id}', 'workerOrderByOne')->middleware('auth:worker');
    });

    Route::controller(WorkerReviewController::class)->prefix('review')->group(function () {
        Route::post('/add-comment', 'addComment')->middleware('auth:client');
        Route::get('/all-review/{postId}', 'postReviewByOne');
    });

    Route::controller(WorkerProfileController::class)->group(function () {
        Route::get('/show-profile', 'showProfile')->middleware('auth:worker');
        Route::get('/edit', 'edit')->middleware('auth:worker');
        Route::post('/update-profile', 'update')->middleware('auth:worker');
        Route::delete('/all-posts/delete', 'destroy')->middleware('auth:worker');
    });
});

Route::controller(AdminNotificationController::class)->middleware('auth:admin')
->prefix('admin/notifications')->group(function () {
    Route::get('/check-notification', 'index');
    Route::get('/check-unreadnotification', 'unread');
    Route::post('/check-readNotifications', 'markReadAll');
    Route::delete('/check-deleteNotifications', 'deleteAll');
    Route::delete('/check-deleteNotifications/{id}', 'delete');
});