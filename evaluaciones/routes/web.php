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

Route::get('/admin', 'AdminController@index')->name('admin_home');

Route::get('/admin/exam/list', 'AdminController@examList')->name('admin_exam_list');

Route::get('/admin/exam/create', 'AdminController@createLink')->name('admin_create_link');
Route::post('/admin/exam/create', 'AdminController@createLink2');

Route::get('/admin/exam/{uuid}', 'ReportsController@view')->name('admin_exam_report');

Route::get('/admin/exam/{uuid}/export', 'WordController@form')->name('admin_exam_export');
Route::post('/admin/exam/{uuid}/export', 'WordController@generate');

Route::get('/exam/{uuid}', 'EvaluationsController@view')->name('exam_form');
Route::post('/exam/{uuid}', 'EvaluationsController@save');

Route::get('/admin/word', 'WordController@form');

Auth::routes(['register' => false, 'reset' => false, 'confirm' => false, 'verify' => false]);
