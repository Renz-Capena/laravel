<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeopleController;
use App\Models\People;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

// create
Route::post("/create",[PeopleController::class,'create'])->name('createFn');

// read
Route::get("/read",[PeopleController::class,'read'])->name('readFn');

// delete
Route::post("/delete",[PeopleController::class,'delete'])->name('deleteFn');

// update Data
Route::get("/updateData",[PeopleController::class,'updateData'])->name('updateDataFn');

// update
Route::post("/update",[PeopleController::class,"update"])->name("updateFn");