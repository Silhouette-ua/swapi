<?php

Auth::routes();

Route::get('/', function () {
    return redirect()->route('people.index');
})->name('index');

Route::get('/people', 'PeopleController@index')->name('people.index');
Route::get('/people/{id}/info', 'PeopleController@info')->name('people.info');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/people/{id}/edit', 'PeopleController@edit')->name('people.edit');
    Route::patch('/people/{id}', 'PeopleController@update')->name('people.update');
    Route::delete('/people/{id}', 'PeopleController@delete')->name('people.delete');
});

Route::get('/error', 'ErrorController@index')->name('error.index');
