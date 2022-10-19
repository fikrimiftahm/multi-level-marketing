<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailReportController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WalletController;

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

Route::get('/', [AuthenticationController::class, 'index'])->name('auth.index');
Route::get('/register', [AuthenticationController::class, 'registerIndex'])->name('auth.register');
Route::post('/register/submit', [AuthenticationController::class, 'registerSubmit'])->name('auth.register.submit');
Route::post('/signin', [AuthenticationController::class, 'signIn'])->name('auth.signin.submit');
Route::get('/signout', [AuthenticationController::class, 'signOut'])->name('auth.signout');

Route::group(['middleware' => 'signedin'], function () {
    Route::group(['prefix' => '/dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    });

    Route::group(['prefix' => '/member'], function () {
        Route::get('/register', [MemberController::class, 'index'])->name('member.register.index');
        Route::post('/register/submit', [MemberController::class, 'registerSubmit'])->name('member.register.submit');

        Route::get('/move', [MemberController::class, 'moveIndex'])->name('member.move.index');
        Route::post('/move/submit', [MemberController::class, 'moveSubmit'])->name('member.move.submit');

        Route::get('/bonus', [MemberController::class, 'bonusIndex'])->name('member.bonus.index');
        Route::post('/bonus/submit', [MemberController::class, 'bonusSubmit'])->name('member.bonus.submit');
    });
});
