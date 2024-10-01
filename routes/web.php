<?php

use App\Http\Controllers\BookResourceController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::prefix('books')->group(function (){
    Route::get('/',              [BookResourceController::class, 'index'])->name('book-index');
    Route::get('/show/{id}',     [BookResourceController::class, 'show']);
    Route::get('/csv',           [BookResourceController::class, 'csv'])->name('book-csv');
    Route::get('/create',        [BookResourceController::class, 'create'])->name('book-create');
    Route::post('/store',        [BookResourceController::class, 'store'])->name('book-store');
    Route::patch('/update/{id}', [BookResourceController::class, 'update'])->name('book-update');
    Route::post('/upload',       [BookResourceController::class, 'uploadCSV'])->name('book-upload');
    Route::get('/export',        [BookResourceController::class, 'bookExport'])->name('book-export');
    Route::delete('/{id}',       [BookResourceController::class, 'destroy']);
});

Route::get('/api/books', [BookResourceController::class, 'getBooks']);
Route::get('/api/books/{id}', [BookResourceController::class, 'findBook']);
