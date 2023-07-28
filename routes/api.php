<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login',[ApiController::class, 'login']);
    Route::post('/register',[ApiController::class, 'register']);
    Route::get('/chat-room/{id}',[ApiController::class, 'chatRoom']);
    Route::post('/store-chat', [ApiController::class, 'storeChat']);
    Route::get('/delete-chat/{id}', [Controller::class, 'delete']);
    Route::get('/delete-client-chat/{id}', [Controller::class, 'deleteTemp']);

});


