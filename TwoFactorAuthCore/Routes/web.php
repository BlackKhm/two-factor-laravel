<?php

use Illuminate\Support\Facades\Route;
use Modules\TwoFactorAuthCore\Http\Controllers\TwoFactorAuthController;
use Modules\TwoFactorAuthCore\Http\Controllers\TwoFactorAuthSessionController;

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

if($webConfig = config('fsc-2fa.two-factor-auth-core.route.web')){
    Route::group($webConfig, function () {
        $routeVerifyPage = config('fsc-2fa.two-factor-auth-core.verify-page');

        Route::get($routeVerifyPage, [TwoFactorAuthSessionController::class, 'index']);
        Route::post($routeVerifyPage, [TwoFactorAuthSessionController::class, 'store']);
    });

}