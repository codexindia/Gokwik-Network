<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->controller('AuthManagement')->group(function () {
    Route::post('/login/LoginOTP','login_OTP');
    Route::post('/login','login_attempt');
    Route::post('/SignUp','SignUP');
    Route::post('/SignUp/SendOTP','SignUP_OTP');
});
Route::middleware(['check_sc','auth:sanctum','CheckBan'])->group(function () {
    Route::prefix('profile')->controller('ProfileManager')->group(function(){
        Route::post('/GetUser','GetUser');
        Route::post('/UpdateUser','UpdateUser');
    });
    Route::prefix('refer')->controller('ReferControll')->group(function(){
        Route::post('/claim','claim');
        Route::post('/skip','skip');
        Route::post('/get_referred_members','get_referred_members');
    });
    Route::prefix('env')->controller('GeneralSettings')->group(function(){
        Route::post('/check_version','check_version');
        Route::post('/check_maintenance','check_maintenance');
    });
    Route::prefix('mining')->controller('MiningModule')->group(function(){
        Route::post('/checkMiningStatus','checkMiningStatus');
        Route::post('/startMining','startMining');  
    });
    Route::prefix('home')->controller('HomeController')->group(function(){
        Route::post('/Statics','Statics');
        Route::post('/popup_banner','popup_banner');
      
    });
});
