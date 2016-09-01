<?php

Route::get('/', 'Neighbourhoods@index')->name('list');

Route::get('/{neighbourhood}/structure_types', 'Neighbourhoods@structure_types')->name('structure_types');
Route::get('/{neighbourhood}/trees', 'Neighbourhoods@trees')->name('trees');
Route::get('/{neighbourhood}/pets', 'Neighbourhoods@pets')->name('pets');
Route::get('/{neighbourhood}/assessment', 'Neighbourhoods@assessment')->name('assessment');
Route::get('/{neighbourhood}/genders', 'Neighbourhoods@genders')->name('genders');
Route::get('/{neighbourhood}/criminal_incidents', 'Neighbourhoods@criminal_incidents')->name('criminal_incidents');
Route::get('/{neighbourhood}/transport_mode', 'Neighbourhoods@transport_mode')->name('transport_mode');
Route::get('/{neighbourhood}/populations', 'Neighbourhoods@populations')->name('populations');
Route::get('/test', 'Neighbourhoods@test');

Route::get('/{neighbourhood}', 'Neighbourhoods@show')->name('neighbourhood.show');

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
