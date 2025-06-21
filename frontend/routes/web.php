<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminFinanceTeamController;
use App\Http\Controllers\AdminCommitteeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CommitteeCertificateController;
use App\Http\Controllers\CommitteeEventController;
use App\Http\Controllers\CommitteeScanQrController;
use App\Http\Controllers\EventFlowController;
use App\Http\Controllers\FinanceTeamRegistrationController;
use App\Http\Controllers\MemberAboutController;
use App\Http\Controllers\MemberCertificateController;
use App\Http\Controllers\MemberRegistrationEventController;
use App\Http\Controllers\MemberScheduleController;
use App\Http\Controllers\MemberSpeakerController;

Route::get('/', function () {
    return view('member.index');
});

Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'submit'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/profile', [LoginController::class, 'getProfile'])->name('profile');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.submit');

Route::prefix('admin')->name('admin.')->middleware('role:1')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

    Route::resource('financeteam', AdminFinanceTeamController::class, [
        'parameters' => ['financeteam' => 'id']
    ]);

    Route::resource('committee', AdminCommitteeController::class, [
        'parameters' => ['committee' => 'id']
    ]);
});


Route::prefix('committee')->name('committee.')->middleware('role:3')->group(function () {
    // Route::get('/', function () {
    //     return view('/member/index');
    // })->name('index');

    Route::resource('event', CommitteeEventController::class, [
        'parameters' => ['event' => 'id']
    ]);

    Route::resource('scanqr', CommitteeScanQrController::class, [
        'parameters' => ['scanqr' => 'id']
    ]);

    Route::resource('certificate', CommitteeCertificateController::class, [
        'parameters' => ['certificate' => 'id']
    ]);
});

Route::prefix('financeteam')->name('financeteam.')->middleware('role:4')->group(function () {
    // Route::get('/', function () {
    //     return view('/member/index');
    // })->name('index');

    Route::resource('registration', FinanceTeamRegistrationController::class, [
        'parameters' => ['registration' => 'id']
    ]);
});


Route::prefix('member')->name('member.')->group(function () {
    Route::get('/', function () {
        return view('/member/index');
    })->name('index');

    Route::resource('schedule', MemberScheduleController::class, [
        'parameters' => ['schedule' => 'id']
    ]);

    Route::resource('speaker', MemberSpeakerController::class, [
        'parameters' => ['speaker' => 'id']
    ]);

    Route::resource('about', MemberAboutController::class, [
        'parameters' => ['about' => 'id']
    ]);

    Route::resource('registration', MemberRegistrationEventController::class, [
        'parameters' => ['registration' => 'id']
    ])->middleware('role:2');

    Route::resource('certificate', MemberCertificateController::class, [
        'parameters' => ['certificate' => 'id']
    ])->middleware('role:2');
});


// Tdk terpakai
Route::get('/index', function () {
    return view('index'); // pastikan ada file resources/views/index.blade.php
})->name('index');
