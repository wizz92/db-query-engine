<?php
use App\Service\DatabaseQueryEngine\Middlewares\DatabaseEngineAuthMiddleware;
use App\Service\DatabaseQueryEngine\Middlewares\AdminAuthMiddleware;
use App\Service\DatabaseQueryEngine\Middlewares\RequestLogMiddleware;

Route::group(['middleware' => RequestLogMiddleware::class], function () {
    Route::post('custom-query/execute', 'CustomQueryController@execute')
         ->middleware([DatabaseEngineAuthMiddleware::class]);
    Route::put('custom-query/{id}/change-status/{status}', 'CustomQueryController@changeStatus');
//        ->middleware(AdminAuthMiddleware::class);
//     Route::group(['middleware' => 'auth.basic.once'], function () {
        Route::get('custom-query/{id}/logs', 'CustomQueryController@queryLogs');
        Route::resource('custom-query', 'CustomQueryController');
//     });
});
