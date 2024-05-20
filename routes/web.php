<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ArchiveController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add your new routes here
    Route::get('/add-patient', function () {
        return view('addpatient');
    })->name('addpatient');

    Route::get('/view-patient', function () {
        return view('viewpatient');
    })->name('viewpatient');

    Route::get('/setting', function () {
        return view('setting');
    })->name('setting');

    Route::post('/update-starting-value', [ArchiveController::class, 'updateStartingValue'])->name('update.starting.value');
    Route::get('/setting', [ArchiveController::class, 'setting'])->name('setting');
    Route::post('/update-storage-path', [ArchiveController::class, 'updateStoragePath'])->name('update.storage.path');
    Route::get('/downloadzip/{id}', [ArchiveController::class, 'download'])->name('zip');
    Route::get('/storezip/{id}', [ArchiveController::class, 'downloadPath'])->name('store.zip');

    Route::resource('archives', ArchiveController::class); // Note the plural 'archives'
    Route::resource('patients', PatientController::class);
    Route::resource('files', FileController::class);
});

require __DIR__ . '/auth.php';