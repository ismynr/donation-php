<?php

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

Route::resource('donasi', 'DonasiController');
Route::resource('category', 'CategoryController');
Route::resource('penerima', 'PenerimaController');
Route::resource('pengurus', 'PengurusController');
Route::resource('donatur', 'DonaturController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
