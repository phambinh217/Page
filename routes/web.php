<?php 

Route::group(['package' => 'page', 'middleware' => ['web'], 'namespace' => 'Admin', 'prefix' => 'admin/page'], function () {
    Route::get('/', 'PageController@index')->name('admin.page.index');
    Route::get('create', 'PageController@create')->name('admin.page.create');
    Route::post('/', 'PageController@store')->name('admin.page.store');
    Route::get('{page}', 'PageController@show')->name('admin.page.show');
    Route::get('{page}/edit', 'PageController@edit')->name('admin.page.edit');
    Route::put('{page}', 'PageController@update')->name('admin.page.update');
    Route::put('{page}/disable', 'PageController@disable')->name('admin.page.disable');
    Route::put('{page}/enable', 'PageController@enable')->name('admin.page.enable');
    Route::delete('/{page}', 'PageController@destroy')->name('admin.page.destroy');
});
