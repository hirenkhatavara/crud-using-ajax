<?php

use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('form');
// });

// Route::get('/load-data',[UserController::class,'index'])->name('loadData');
// Route::post('form/store',[UserController::class,'store'])->name('form.store');
Route::get('/', [UserController::class,'index'])->name('user.index');
Route::post('user/store', [UserController::class,'store'])->name('user.store');
Route::post('user/update/{id}', [UserController::class,'update'])->name('user.update');
Route::delete('user/destroy/{id}', [UserController::class,'destroy'])->name('user.destroy');
Route::delete('user/bulk-delete', [UserController::class,'bulkDelete']);
Route::get('/user/hobbies', [UserController::class, 'getAllHobbies']);


