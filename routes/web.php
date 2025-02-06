<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::get('/', function () {
    return redirect('/home');
});
Route::post('/broadcasting/auth', function () {
    return auth()->user();
})->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/messages', [MessageController ::class, 'chatList'])->name('messages.show');

    Route::get('/chat/{user}', [ChatController::class, 'index'])->name('chat');
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');


    Route::get('/friends', [FriendController::class, 'show'])->name('friends.show');
    Route::post('/friends/request/{id}', [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::post('/friends/accept/{id}', [FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/decline/{id}', [FriendController::class, 'declineRequest'])->name('friends.decline');
    Route::post('/friends/remove/{id}', [FriendController::class, 'removeFriend'])->name('friends.remove');
    Route::get('/friends/requests', [FriendController::class, 'friendRequests'])->name('friends.requests');




    // Домашняя страница пользователя (лента постов)
    Route::get('/user/{login}', [UserController::class, 'show'])->name('user.show');
    Route::get('/settings/{login}', [UserController::class, 'settings'])->name('settings');
    Route::put('/settings', [UserController::class, 'updateSettings'])->name('settings.update');


    Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');


    // CRUD для постов
    Route::controller(PostController::class)->prefix('user/posts')->name('posts.')->group(function () {
        Route::get('/', 'index')->name('index'); // Список постов
        Route::post('/', 'store')->name('store'); // Создание поста
        Route::put('/{post}', 'update')->name('update'); // Обновление поста
        Route::get('/load', 'loadMore')->name('loadMore'); // Загрузка постов для infinite scroll
    });
});

Route::get('/user/posts/load', [PostController::class, 'loadMore'])->name('posts.loadMore');
