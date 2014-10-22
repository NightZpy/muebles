<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
 */

Event::listen('Muebles.Users.Events.UserRegistered', function($event) {
	Mail::send('users.emails.activate-user', array('user' => $event->user), function($message)
	{
		$message->to('nightzpy@gmail.com', 'Lenyn')
			->from('informacion@presentatenlaweb.com', 'Presentatenlaweb Atención al cliente')
			->subject('Un nuevo usuario se ha registrado!');
	});
});

Event::listen('Muebles.Users.Events.UserActivate', function($event) {
	$user = $event->user;
	Mail::send('users.emails.user-activate', array('user' => $user), function($message) use ($user)
	{
		$message->to($user->email, $user->nombre)
			->from('informacion@grupodos.com', 'Grupo Dos S.L.')
			->subject('Felicitaciones: Hemos admitido tu ingreso!');
	});
});



Route::get('/error-404', [
	'as' => 'error_path',
	'uses' => 'PagesController@error404'
]);

Route::get('/', [
	'as' => 'home',
	'uses' => 'PagesController@home'
]);

/**
 * Registration!
 */
Route::get('register', [
	'as' => 'register_user_path',
	'uses' => 'UserController@create'
]);

Route::post('register', [
	'as' => 'register_user_path',
	'uses' => 'UserController@store'
]);

Route::get('user-registered/{email}/{nombres}', [
	'as' => 'registered_user_path',
	'uses' => 'UserController@registered'
]);

Route::get('activate-user/{id}', [
	'as' => 'activate_user_path',
	'uses' => 'UserController@activateUser'
]);

Route::get('login', [
	'as' => 'login_path',
	'uses' => 'UserController@loginCreate'
]);

Route::post('login', [
	'as' => 'login_path',
	'uses' => 'UserController@login'
]);

/**
 * Destroy session
 */
Route::get('logout', [
	'as' => 'logout_path',
	'uses' => 'UserController@destroySession'
]);

/**
 * Catalogo routes
 */
Route::get('contacto', [
	'as' => 'contact_path',
	'uses' => 'ContactController@index'
]);

/**
 * Catalogo routes
 */
Route::get('catalogo', [
	'as' => 'catalogo_path',
	'uses' => 'CatalogoController@index'
]);
