<?php

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
    return redirect('/home');
});
Route::get('/admin', function () {
    return redirect('/admin/home');
});
Route::get('/konstruktor', function () {
    return redirect('/konstruktor/home');
});

Auth::routes();

Route::middleware(['auth', 'admin'])->namespace('Admin')->prefix('admin')->group(function(){
		Route::get("/home","HomeController@index");
		Route::get('/magang', 'MagangController@index');
		Route::post('/magang/validasi', 'MagangController@validasi');
		Route::post('/magang/completed', 'MagangController@completed');
		Route::post('/magang/delete', 'MagangController@delete');

		Route::get('/magang/load', 'MagangController@load');
		Route::post('/viewpdf', 'SuratController@viewpdf');
});
Route::middleware(['auth', 'konstruktor'])->namespace('Konstruktor')->prefix('konstruktor')->group(function(){
		Route::get("/home","HomeController@index");
		Route::get('/magang', 'MagangController@index');
		Route::post('/viewpdf', 'SuratController@viewpdf');

});


Route::resource('/magang','MagangController')->middleware(['auth','user']);
Route::post('/magang/addkonstruktor','MagangController@addkonstruktor')->middleware(['auth','user']);
Route::resource('/biodata','BiodataController')->middleware(['auth','user']);



Route::get('/home', 'HomeController@index')->name('home')->middleware(['auth','user']);
// Route::get('/pengembangan', 'HomeController@pengembangan')->name('pengembangan');


Route::get('/logout', function(){
	Auth::logout();
	return redirect('/login');

});

Route::get('/test', "MagangController@test");