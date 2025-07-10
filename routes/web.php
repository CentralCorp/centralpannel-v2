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
use App\Http\Controllers\InstallController;
use App\Http\Controllers\AdminConfigController;
use App\Http\Controllers\users\AdminUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsExportController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\AdminBgController;
use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\FileController;
use App\Http\Controllers\api\ModController;


Auth::routes(['register' => false]);

// Routes d'installation
Route::get('/install', [InstallController::class, 'showDatabase'])->name('install.database');
Route::post('/install/database', [InstallController::class, 'database'])->name('install.database.store');
Route::get('/install/admin', [InstallController::class, 'showAdmin'])->name('install.admin');
Route::post('/install/admin', [InstallController::class, 'install'])->name('install.admin.store');
Route::get('/install/finish', [InstallController::class, 'finish'])->name('install.finish');

// Redirection de la route racine vers la page de connexion ou admin selon l'état de connexion
Route::get('/', function () {
    // Vérifier si l'installation est terminée
    if (!File::exists(storage_path('installed')) || config('app.key') === \App\Http\Controllers\InstallController::TEMP_KEY) {
        return redirect()->route('install.database');
    }
    
    if (Auth::check()) {
        return redirect()->route('admin.index');
    }
    return redirect()->route('login');
});

// Routes avec le préfixe 'admin' (groupées)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

    Route::get('/config', [AdminConfigController::class, 'show'])->name('admin.config');
    Route::post('/config', [AdminConfigController::class, 'update'])->name('admin.config.update');

    Route::get('/general', [AdminController::class, 'general'])->name('admin.general');
    Route::post('/general/update', [AdminController::class, 'updateGeneral'])->name('admin.general.update');
    Route::get('/security', [AdminSecurityController::class, 'show'])->name('admin.security');
    Route::post('/security/update', [AdminSecurityController::class, 'update'])->name('admin.security.update');

    Route::get('/server', [AdminServerController::class, 'show'])->name('admin.server');
    Route::post('/server/update', [AdminServerController::class, 'update'])->name('admin.server.update');
    Route::post('/server/set-default', [AdminServerController::class, 'setDefaultServer'])->name('admin.server.set-default');

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
    Route::get('/loader/builds/', [AdminLoaderController::class, 'getForgeBuilds'])->name('admin.loader.builds');
    Route::get('/loader/fabric-versions', [AdminLoaderController::class, 'getFabricVersions'])->name('admin.loader.fabric-versions');

    Route::get('/rpc', [AdminRpcController::class, 'show'])->name('admin.rpc');
    Route::post('/rpc/update', [AdminRpcController::class, 'update'])->name('admin.rpc.update');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users/add', [AdminUserController::class, 'add'])->name('admin.users.add');
    Route::delete('/users/delete/{id}', [AdminUserController::class, 'delete'])->name('admin.users.delete');
    Route::get('/users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');

    Route::get('/settings/export', [SettingsExportController::class, 'export'])->name('admin.settings.export');
    Route::post('/settings/import', [SettingsExportController::class, 'import'])->name('admin.settings.import');

    Route::get('/bg', [AdminBgController::class, 'index'])->name('admin.bg');
    Route::post('/bg/update', [AdminBgController::class, 'update'])->name('admin.bg.update');

    // Routes pour les thèmes marketplace
    Route::get('/theme', [ThemeController::class, 'index'])->name('admin.theme.index');
    Route::post('/theme/download/{id}', [ThemeController::class, 'download'])->name('admin.theme.download');
    Route::get('/theme/purchase/{id}', [ThemeController::class, 'purchase'])->name('admin.theme.purchase');

    Route::get('/update', [\App\Http\Controllers\Admin\UpdateController::class, 'index'])->name('admin.update');
    Route::post('/update', [\App\Http\Controllers\Admin\UpdateController::class, 'update'])->name('admin.update.run');

});

// Routes sans le préfixe 'admin'

Route::get('/file-manager', function () {
    return view('admin.file-manager');
})->name('admin.file-manager');

Route::prefix('utils')->group(function () {
    Route::get('/api', [ApiController::class, 'getOptions']);
    Route::get('/mods', [ModController::class, 'getMods']);
});
Route::get('/data', [FileController::class, 'getFiles']);
