Route::prefix('azuriom')->group(function () {
    Route::get('/servers', [AzuriomApiController::class, 'getServers']);
    Route::get('/roles', [AzuriomApiController::class, 'getRoles']);
    Route::get('/users', [AzuriomApiController::class, 'getUsers']);
    Route::get('/money', [AzuriomApiController::class, 'getMoney']);
    Route::get('/social', [AzuriomApiController::class, 'getSocial']);
}); 