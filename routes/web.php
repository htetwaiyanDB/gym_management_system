<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/csrf-token', function() {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});


require __DIR__.'/auth.php';
