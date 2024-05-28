<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ZipExtractController;
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
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/add-patient', function () {
        return view('addpatient');
    })->name('addpatient');

    Route::get('/view-patient', function () {
        return view('viewpatient');
    })->name('viewpatient');

    Route::get('/view-zip', function () {
        return view('viewzip');
    })->name('viewzip');

    Route::get('/setting', function () {
        return view('setting');
    })->name('setting');

    Route::get('/extract', function () {
        return view('viewzip');
    })->name('extract');

    Route::post('/update-starting-value', [ArchiveController::class, 'updateStartingValue'])->name('update.starting.value');
    Route::get('/setting', [ArchiveController::class, 'setting'])->name('setting');
    Route::post('/update-storage-path', [ArchiveController::class, 'updateStoragePath'])->name('update.storage.path');
    Route::get('/downloadzip/{id}', [ArchiveController::class, 'download'])->name('zip');
    Route::get('/storezip/{id}', [ArchiveController::class, 'downloadPath'])->name('store.zip');
    Route::post('/update-case-number-format', [App\Http\Controllers\ArchiveController::class, 'updateCaseNumberFormat'])->name('update.case.number.format');


    Route::get('/zip', function () {
        return view('zip');
    })->name('zip_form');

    Route::post('/upload_zip', [ZipExtractController::class, 'extractAndListFiles'])->name('upload_zip');
    Route::get('/view_file/{filename}', [ZipExtractController::class, 'viewFile'])->name('view_file')->where('filename', '.*');

    Route::resource('archives', ArchiveController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('files', FileController::class);
    Route::resource('zips', ZipExtractController::class);


});

require __DIR__ . '/auth.php';