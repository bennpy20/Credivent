<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('member.index');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('/admin/index');
    })->name('index');
});

Route::prefix('member')->name('member.')->group(function () {
    Route::get('/', function () {
        return view('/member/index');
    })->name('index');
});


Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/profile', [LoginController::class, 'getProfile'])->name('profile');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');

Route::get('/index', function () {
    return view('index'); // pastikan ada file resources/views/index.blade.php
})->name('index');

