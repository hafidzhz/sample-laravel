<?php

use App\Http\Controllers\API\UserAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/get-data', [UserAPIController::class, 'getData'])->name('api.get_data');

Route::post('/save-data', [UserAPIController::class, 'saveData'])->name('api.save_data');
Route::delete('/delete-data', [UserAPIController::class, 'deleteData'])->name('api.delete_data');
Route::post('/update-data/{id}', [UserAPIController::class, 'updateData'])->name('api.update_data');
