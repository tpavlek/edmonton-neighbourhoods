<?php

Route::get('/', 'Neighbourhoods@index')->name('list');

Route::get('/{neighbourhood}/structure_types', 'Neighbourhoods@structure_types')->name('structure_types');
Route::get('/{neighbourhood}/trees', 'Neighbourhoods@trees')->name('trees');
Route::get('/{neighbourhood}/pets', 'Neighbourhoods@pets')->name('pets');
Route::get('/{neighbourhood}/assessment', 'Neighbourhoods@assessment')->name('assessment');
Route::get('/{neighbourhood}/genders', 'Neighbourhoods@genders')->name('genders');

Route::get('/{neighbourhood}', 'Neighbourhoods@show');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
