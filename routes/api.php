<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsearAuthController;
use App\Http\Controllers\Api\NotesController;




Route::post('/register',[UsearAuthController::class,'register']);
Route::post('/login',[UsearAuthController::class,'login']);
Route::post('/logout',[UsearAuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/profile',[UsearAuthController::class,'profile'])->middleware('auth:sanctum');
Route::post('/upload-profile-picture',[UsearAuthController::class,'uploadProfilePicture'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/list-notes', [NotesController::class, 'index']);
    Route::post('/add-notes', [NotesController::class, 'store']);
    Route::get('/list-notes/{id}', [NotesController::class, 'show']);
    Route::post('/edit-notes/{id}', [NotesController::class, 'update']);
    Route::delete('/list-notes/{id}', [NotesController::class, 'destroy']);

    Route::get('/notes-trash', [NotesController::class, 'trash']);
    Route::post('/notes-restore/{id}', [NotesController::class, 'restore']);


});


