<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['prefix' => 'admin'], function() {

	Route::resource('contents', 'ContentsController');
	Route::resource('locales', 'LocalesController');
	Route::resource('menus', 'MenusController');
	Route::resource('menulinks', 'MenuLinksController');

});


Route::group(['prefix' => 'admin'], function() {

	Route::get('pages', 'PageController@index');
	Route::get('pages/{hash}/preview', 'FrontendController@previewPage');
	Route::get('pages/published', 'PageController@published_pages');
	Route::get('pages/drafts', 'PageController@draft_pages');
	Route::get('pages/trash', 'PageController@deleted_pages');
	Route::get('pages/create', 'PageController@create');
	Route::get('pages/{id}/edit', 'PageController@edit');
	Route::get('pages/{id}/delete', 'PageController@delete');
	Route::get('pages/{id}/restore', 'PageController@restore');
	Route::get('pages/{id}/destroy', 'PageController@destroy');
	Route::get('pages/{id}/versions', 'PageController@versions');

	Route::post('pages/create', 'PageController@store');
	Route::post('pages/{id}/update', 'PageController@update');
	Route::post('pages/bulk-publish', 'PageController@bulk_publish');
	Route::post('pages/bulk-draft', 'PageController@bulk_draft');
	Route::post('pages/bulk-delete', 'PageController@bulk_delete');
	Route::post('pages/bulk-restore', 'PageController@bulk_restore');
	Route::post('pages/bulk-destroy', 'PageController@bulk_destroy');
	Route::post('pages/{id}/select-version', 'PageController@select_version');

});

Route::get('/', 'WelcomeController@index');
// Route::get('/', ['as' => 'home', function() {
//     return View::make('hello');
// }]);
Route::get('/home', 'HomeController@index');



//Route::get('{page}', 'FrontendController@get_page')->where('page', '.*');



Route::get('articles', 'ArticleController@index');
Route::get('articles/{id}', 'ArticleController@show');
Route::put('articles/{id}', 'ArticleController@update');
Route::post('articles', 'ArticleController@store');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

