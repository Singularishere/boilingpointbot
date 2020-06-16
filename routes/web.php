<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\DataController;

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
});
    Route::get('getwebhook','MainController@getWebHook')->name('getwebhook');
Route::get('setwebhook','MainController@setWebHook')->name('setwebhook');
//Route::get('getwebhook','MainController@getWebHook')->name('getwebhook');
Route::post('/home','MainController@index')->name('home');
Route::get('/data','DataController@index')->name('data');
Route::post(Telegram::getAccessToken(),'DataController@handleTelegramData');
Route::get('authorizeAPI','DataController@authorizeLeaderApi');
Route::get('getAccessCode','DataController@getAccessCode');
Route::get('getCities','DataController@getCitiesApi');
Route::get('getEvents','ApiController@getUserSubscribeEvents');
Route::get('tester','DataController@test');
//Route::match(array('GET', 'POST'), '/home','MainController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
