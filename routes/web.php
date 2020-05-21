<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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
//
Route::get('/', function () {
    return view('welcome');
});
Route::match(['post','get'],'register',function (){
   Auth::logout();
   return redirect('/');
})->name('register');
Route::get('setwebhook','MainController@setWebHook')->name('setwebhook');
Route::get('getwebhook','MainController@getWebHook')->name('getwebhook');
Route::post('/home','MainController@index')->name('home');
Route::get('/data','DataController@index')->name('data');
Route::post(Telegram::getAccessToken(),function (){
   Telegram::commandsHandler(true);
});
//Route::match(array('GET', 'POST'), '/home','MainController@index');
