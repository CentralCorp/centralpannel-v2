<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRpcController;
use App\Http\Controllers\AdminModController;
use App\Http\Controllers\AdminSecurityController;
use App\Http\Controllers\AdminServerController;
use App\Http\Controllers\AdminUIController;
use App\Http\Controllers\AdminWhitelistController;
use App\Http\Controllers\AdminLoaderController;
use App\Http\Controllers\AdminIgnoreController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\AdminConfigController;
use Illuminate\Support\Facades\Auth;

Auth::routes(['register' => false]);

// Routes d'installation
Route::get('/install', [InstallController::class, 'show'])->name('install.show');
Route::post('/install', [InstallController::class, 'install'])->name('install.process');

// Redirection de la route racine vers la page de connexion ou admin selon l'état de connexion
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.index');
    }
    return redirect()->route('login');
});

// Routes avec le préfixe 'admin' (groupées)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::get('/config', [AdminConfigController::class, 'show'])->name('admin.config');
    Route::post('/config', [AdminConfigController::class, 'update'])->name('admin.config.update');

    Route::get('/general', [AdminController::class, 'general'])->name('admin.general');
    Route::post('/general/update', [AdminController::class, 'updateGeneral'])->name('admin.general.update');
    Route::get('/security', [AdminSecurityController::class, 'show'])->name('admin.security');
    Route::post('/security/update', [AdminSecurityController::class, 'update'])->name('admin.security.update');

    Route::get('/server', [AdminServerController::class, 'show'])->name('admin.server');
    Route::post('/server/update', [AdminServerController::class, 'update'])->name('admin.server.update');
    Route::post('/admin/server/set-default', [AdminServerController::class, 'setDefaultServer'])->name('admin.server.set-default');

    Route::get('/ui', [AdminUIController::class, 'show'])->name('admin.ui');
    Route::post('/ui/update', [AdminUIController::class, 'update'])->name('admin.ui.update');

    Route::get('/whitelist', [AdminWhitelistController::class, 'index'])->name('admin.whitelist');
    Route::post('/whitelist', [AdminWhitelistController::class, 'store'])->name('admin.whitelist.store');
    Route::delete('/whitelist/user/{id}', [AdminWhitelistController::class, 'destroyUser'])->name('admin.whitelist.destroyUser');
    Route::delete('/whitelist/role/{id}', [AdminWhitelistController::class, 'destroyRole'])->name('admin.whitelist.destroyRole');

    Route::get('/ignore', [AdminIgnoreController::class, 'index'])->name('admin.ignore');
    Route::post('/ignore', [AdminIgnoreController::class, 'store'])->name('admin.ignore.store');
    Route::delete('/ignore/folder/{id}', [AdminIgnoreController::class, 'destroyFolder'])->name('admin.ignore.destroyFolder');

    Route::get('/mods', [AdminModController::class, 'index'])->name('admin.mods');
    Route::post('/mods/add', [AdminModController::class, 'addOptionalMod'])->name('admin.mods.addOptional');
    Route::post('/mods/update', [AdminModController::class, 'updateOptionalMod'])->name('admin.mods.updateOptional');
    Route::post('/mods/delete/{id}', [AdminModController::class, 'deleteOptionalMod'])->name('admin.mods.delete');
    Route::get('/mods/{id}', [AdminModController::class, 'getOptionalModDetails'])->name('admin.mods.getOptionalModDetails');

    Route::get('/loader', [AdminLoaderController::class, 'index'])->name('admin.loader');
    Route::post('/loader/update', [AdminLoaderController::class, 'update'])->name('admin.loader.update');
    Route::get('/loader/builds', [AdminLoaderController::class, 'getForgeBuilds'])->name('admin.loader.builds');

    Route::get('/rpc', [AdminRpcController::class, 'show'])->name('admin.rpc');
    Route::post('/rpc/update', [AdminRpcController::class, 'update'])->name('admin.rpc.update');
});

// Routes sans le préfixe 'admin'

Route::get('/file-manager', function () {
    return view('admin.file-manager');
})->name('admin.file-manager');

// Route pour les fichiers
Route::get('/files', [FileController::class, 'index']);
