<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CelebrateController;
use App\Http\Controllers\PrometheusController;
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

Route::get('metrics',[PrometheusController::class,'index']);

Route::group(['middleware' => 'language'],function(){
    Route::get('/', function () {
        return redirect()->route('celebrations.create');
    });

    Route::resource('celebrations', CelebrateController::class)->only(['create','store','show']);

    Route::get('{id}/{name}',[CelebrateController::class,'visit']);

    Route::Post('changelang',[CelebrateController::class,'changeLang'])->name('changeLanguage');

});


