<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/convert', 'HomeController@convert')->name('convert');

Route::post('/convert', 'DocumentController@convert')->name('file.convert');
Route::post('/convertToEncrypt', 'DocumentController@convertToEncrypt')->name('file.convertToEncrypt');
Route::post('/convertToDecrypt', 'DocumentController@convertToDecrypt')->name('file.convertToDecrypt');
Route::post('file-upload/upload-large-files', 'DocumentController@uploadLargeFiles')->name('files.upload.large');
Route::get('/forceDonwload/{newfileNameWithExt}', 'DocumentController@forceDonwload')->name('file.forceDonwload');
Route::post('/encrypt', 'DocumentController@encrypt')->name('file.encrypt');
Route::post('/decrypt', 'DocumentController@decrypt')->name('file.decrypt');

