<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Домашняя страница пользователя (лента постов)
    Route::get('/user/home', [PostController::class, 'index'])->name('home');
    Route::get('/user/settongs', [PostController::class, 'settings'])->name('settings');
    Route::put('/settings', [PostController::class, 'updateSettings'])->name('settings.update');


    // CRUD для постов
    Route::controller(PostController::class)->prefix('user/posts')->name('posts.')->group(function () {
        Route::get('/', 'index')->name('index'); // Список постов
        Route::post('/', 'store')->name('store'); // Создание поста
        Route::put('/{post}', 'update')->name('update'); // Обновление поста
        Route::delete('/{post}', 'destroy')->name('destroy'); // Удаление поста
        Route::get('/load', 'loadMore')->name('loadMore'); // Загрузка постов для infinite scroll
    });
});

Route::get('/user/posts/load', [PostController::class, 'loadMore'])->name('posts.loadMore');
