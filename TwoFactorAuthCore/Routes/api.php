<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\TwoFactorAuthCore\Http\Controllers\Api\TwoFactorAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
if(config('fsc-2fa.two-factor-auth-core.route.api')){
    Route::prefix('two-factor')
    ->name('two-factor.')
    ->middleware(['auth:web,api'])
    ->group(function () {
        Route::get('/', [TwoFactorAuthController::class, 'index'])->name('index');
            
        Route::post('/', [TwoFactorAuthController::class, 'store'])->name('store');

        Route::delete('/', [TwoFactorAuthController::class, 'destroy'])->name('destroy');

        Route::post('confirm', [TwoFactorAuthController::class, 'storeConfirm'])->name('confirm');
        
        Route::post('recovery-code', [TwoFactorAuthController::class, 'storeRecoveryCode'])->name('recovery-code.store');

    });
}
