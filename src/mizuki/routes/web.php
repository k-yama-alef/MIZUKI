<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\mzwp0010Controller;
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
info('web.php');//デバック

// データベーステスト
// Route::get('test',[App\Http\Controllers\TestController::class,'test'])->name('test');
// Route::post("testcheck", 'App\Http\Controllers\TestController@check');
// Route::post("testcheck", 'App\Http\Controllers\TestController@check');
// Route::post('testcheck', [App\Http\Controllers\TestController::class,'check']);


// プロジェクト用
// ***品質管理
Route::post("/project/mzwp0010", 'App\Http\Controllers\project\mzwp0010Controller@FnWrite')->name('mzwp0010_write');
// Route::get("/project/mzwp0011", 'App\Http\Controllers\project\mzwp0010Controller@index_Kensaku');
Route::get("/project/mzwp0011", 'App\Http\Controllers\project\mzwp0010Controller@index_Kensaku')->name('mzwp0011');
Route::get("/project/mzwp0010", 'App\Http\Controllers\project\mzwp0010Controller@FnRead')->name('mzwp0010_read');
Route::post("hinban_check", 'App\Http\Controllers\project\mzwp0010Controller@FnHinban_check');

// ***教育訓練
Route::post("/project/mzwp0020", 'App\Http\Controllers\project\mzwp0020Controller@FnWrite')->name('mzwp0020_write');
Route::get("/project/mzwp0021", 'App\Http\Controllers\project\mzwp0020Controller@index_Kensaku')->name('mzwp0021');
Route::get("/project/mzwp0020", 'App\Http\Controllers\project\mzwp0020Controller@FnRead')->name('mzwp0020_read');

// ***文書管理
Route::post("/project/mzwp0030", 'App\Http\Controllers\project\mzwp0030Controller@FnWrite')->name('mzwp0030_write');
Route::get("/project/mzwp0031", 'App\Http\Controllers\project\mzwp0030Controller@index_Kensaku')->name('mzwp0031');
Route::get("/project/mzwp0030", 'App\Http\Controllers\project\mzwp0030Controller@FnRead')->name('mzwp0030_read');

// ***計測器・治工具管理
Route::post("/project/mzwp0040", 'App\Http\Controllers\project\mzwp0040Controller@FnWrite')->name('mzwp0040_write');
Route::get("/project/mzwp0041", 'App\Http\Controllers\project\mzwp0040Controller@index_Kensaku')->name('mzwp0041');
Route::get("/project/mzwp0040", 'App\Http\Controllers\project\mzwp0040Controller@FnRead')->name('mzwp0040_read');

// プロジェクト用終了
// phpinfo
Route::get("/phpinfo", 'App\Http\Controllers\phpinfoController@check');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// パスワード変更
Route::get('/password/change', [App\Http\Controllers\Auth\ChangePasswordController::class,'showChangePasswordForm'])->name('password.form');
Route::post('/password/change', [App\Http\Controllers\Auth\ChangePasswordController::class,'ChangePassword'])->name('password.change');

Auth::routes();
