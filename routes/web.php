<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use Illuminate\Support\Collection;

Route::get('deleteacount.html',function(){
    return redirect('https://forms.gle/n4Y9ZcjPuNa3szEp9');
});
