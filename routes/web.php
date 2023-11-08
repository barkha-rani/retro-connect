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

Route::get('/','TempController@baseRedirect');

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', 'TempController@dashboard')->name('dashboard');
    Route::get('/manage-teams', 'TempController@teams')->name('teams');
    Route::get('/manage-teams/details', 'TempController@teamDetails')->name('teams.details');
    Route::get('/retro-boards', 'TempController@boards')->name('retro-boards');
    Route::get('/retro-boards/details', 'TempController@boardDetails')->name('retro-boards.details');
    
    // Route::resource('teams', 'TeamController')->except(['show','update']);
    
    Route::get('teams', 'TeamController@index')->name('teams.index');
    Route::get('teams/my-team', 'TeamController@myTeam')->name('teams.myTeam');
    Route::post('teams', 'TeamController@store')->name('teams.store');
    Route::post('teams/add-member', 'TeamController@addMember')->name('teams.addMember');
    Route::get('teams/{uuid}', 'TeamController@show')->name('teams.show');
    Route::put('teams/{uuid}', 'TeamController@update')->name('teams.update');
    Route::delete('teams/{uuid}', 'TeamController@destroy')->name('teams.destroy');

    Route::get('boards', 'BoardController@index')->name('boards.index');
    Route::post('boards', 'BoardController@store')->name('boards.store');
    Route::get('boards/{uuid}', 'BoardController@show')->name('boards.show');
    Route::post('boards/{uuid}', 'BoardController@update')->name('boards.update');
    Route::delete('boards/{uuid}', 'BoardController@destroy')->name('boards.destroy');
    
    Route::post('boards/cards/store', 'BoardController@storeCard')->name('cards.store');
    Route::post('boards/cards/like', 'BoardController@likeCard')->name('cards.like');
    Route::post('boards/cards/destroy', 'BoardController@destroyCard')->name('cards.destroy');
    Route::post('boards/cards/move', 'BoardController@moveCard')->name('cards.move');

    // Route::resource('boards', 'BoardsController');
    // Route::resource('cards', 'CardController');
    // Route::resource('comments', 'CommentController');
});

Auth::routes();
