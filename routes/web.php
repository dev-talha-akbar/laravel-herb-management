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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/submission/{id}', 'HomeController@submission');
Route::get('/submission_download/{id}', 'HomeController@submission_download');
Route::get('/getsignresults/{sign_ids}', 'HomeController@getSignResults');
Route::get('/options', 'HomeController@options');
Route::post('/search', 'HomeController@search');
Route::post('/submit', 'HomeController@submit');
Route::post('/admin_signs_symptoms/{id}/group', 'HomeController@group');
Route::put('/submission/{id}', 'HomeController@editSubmission');
