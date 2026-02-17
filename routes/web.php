<?php

use App\Http\Controllers\RecordingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RecordingController::class, 'index'])->name('recordings.index');
Route::post('/recordings', [RecordingController::class, 'store'])->name('recordings.store');
Route::get('/v/{token}', [RecordingController::class, 'show'])->name('recordings.show');
Route::get('/recordings/{recording}/download', [RecordingController::class, 'download'])->name('recordings.download');
Route::post('/recordings/{recording}/upload-drive', [RecordingController::class, 'uploadToDrive'])->name('recordings.upload-drive');
Route::delete('/recordings/{recording}', [RecordingController::class, 'destroy'])->name('recordings.destroy');
