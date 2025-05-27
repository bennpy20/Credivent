<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminFinanceTeamController;
use App\Http\Controllers\AdminCommitteeController;
use App\Http\Controllers\CommitteeEventController;
use App\Http\Controllers\EventFlowController;
use App\Http\Controllers\MemberScheduleController;

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
    Route::get('/', function () {
        return view('/admin/index');
    })->name('index');

    Route::resource('financeteam', AdminFinanceTeamController::class, [
        'parameters' => ['financeteam' => 'id']
    ]);

    Route::resource('committee', AdminCommitteeController::class, [
        'parameters' => ['committee' => 'id']
    ]);
});


Route::prefix('committee')->name('committee.')->group(function () {
    // Route::get('/', function () {
    //     return view('/member/index');
    // })->name('index');

    Route::resource('event', CommitteeEventController::class, [
        'parameters' => ['event' => 'id']
    ]);

    // Step 1 - Buat event baru
    Route::get('create', [EventFlowController::class, 'showCreateEventForm'])->name('event.create');
    Route::post('store', [EventFlowController::class, 'storeEvent'])->name('event.save');

    // Step 2 - Tambah sesi untuk event
    Route::get('{event}/sessions/create', [EventFlowController::class, 'showAddSessionForm'])->name('session.create');
    Route::post('{event}/sessions/store', [EventFlowController::class, 'storeSession'])->name('session.save');

    // Step 3 - Tambah pembicara untuk event
    Route::get('{event}/speakers/create', [EventFlowController::class, 'showAddSpeakerForm'])->name('speaker.create');
    Route::post('{event}/speakers/store', [EventFlowController::class, 'storeSpeaker'])->name('speaker.save');

    // Publish event (finalisasi)
    Route::put('{event}/publish', [EventFlowController::class, 'publishEvent'])->name('event.publish');

    // Batalkan event (hapus draft)
    Route::delete('{event}/cancel', [EventFlowController::class, 'cancelEvent'])->name('event.cancel');
});


Route::prefix('member')->name('member.')->group(function () {
    Route::get('/', function () {
        return view('/member/index');
    })->name('index');

    Route::resource('schedule', MemberScheduleController::class, [
        'parameters' => ['schedule' => 'id']
    ]);
});


// Tdk terpakai
Route::get('/index', function () {
    return view('index'); // pastikan ada file resources/views/index.blade.php
})->name('index');
