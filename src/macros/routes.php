<?php

if (!Route::hasMacro('resource_')) {
    Route::macro('resource_', function ($name, $controller, $options = []) {
        Route::get($name.'/activate/{id}', $controller.'@activate')->name($name.'.activate');
        Route::get($name.'/deactivate/{id}', $controller.'@deactivate')->name($name.'.deactivate');
        Route::get($name.'/excel/create', $controller.'@excelCreate')->name($name.'.excelCreate');
        Route::post($name.'/excel', $controller.'@excelStore')->name($name.'.excelStore');
        Route::get($name.'/excel/download', $controller.'@excelDownload')->name($name.'.excelDownload');
        Route::resource($name, $controller, $options);
    });
}
