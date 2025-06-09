<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\FileController;
use App\Http\Controllers\api\ModController;

Route::get('/centralcorp/options', [ApiController::class, 'getOptions']);
Route::get('/centralcorp/files', [FileController::class, 'getFiles']);
Route::get('/centralcorp/mods', [ModController::class, 'getMods']);




