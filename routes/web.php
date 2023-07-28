<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DateController;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/chatroom/{id}', function ($id) {
    $users = User::where('id',$id)->first();
    $currentUser = Auth::id();
    $user1 = "{$currentUser}" ;
    $user2 = "{$users->id}";
    if (ord($user1[0]) > ord($user2[0])) {
        $roomId = $user1.$user2;
    } else {
        $roomId = $user2.$user1;
    }
    $messages = Message::where('roomId',$roomId)->get();
     return view('user',compact('users','currentUser','roomId','messages'));
});
Route::post('/store-chat', [Controller::class, 'storeChat']);
Route::get('/delete/{id}', [Controller::class, 'delete']);
Route::get('/delete-temp/{id}', [Controller::class, 'deleteTemp']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        $currentUser = Auth::id();
        $users = User::whereNot('id',$currentUser)->get();
        return view('users',compact('users'));
    })->name('dashboard');
});



Route::get('/event',[AdminController::class,'admin']);
Route::get('/add-event',[AdminController::class,'addEvent']);
Route::post('/add-new-event',[AdminController::class,'addNewEvent']);
Route::get('/edit-event/{id}',[AdminController::class,'editEvent']);
Route::get('/delete-event/{id}',[AdminController::class,'deleteEvent']);

Route::get('/date',[DateController::class,'admin']);
Route::get('/add-date',[DateController::class,'addDate']);
Route::post('/add-new-date',[DateController::class,'addNewDate']);
Route::get('/edit-date/{id}',[DateController::class,'editDate']);
Route::get('/delete-date/{id}',[DateController::class,'deleteDate']);
