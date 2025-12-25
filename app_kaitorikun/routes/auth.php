<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('user/list',[RegisteredUserController::class, 'list'])
              ->name('auth.list');

    Route::get('user/{id}/detail',[RegisteredUserController::class, 'detail'])
              ->name('auth.detail');          
    Route::get('user/{id}/edit', [RegisteredUserController::class, 'edit'])->name('user.edit');
    
    Route::put('user/{id}/update', [RegisteredUserController::class, 'update'])->name('user.update');

    Route::get('user/{id}/delete_confirm', [RegisteredUserController::class, 'delete_confirm'])->name('user.delete_confirm');
    
    // 実行処理（DELETE）
    Route::delete('user/{id}', [RegisteredUserController::class, 'destroy'])->name('user.destroy');
    


    Route::post('', [RegisteredUserController::class, 'update']);


    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

 
});

Route::middleware('auth')->group(function () {


    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
   
  

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
