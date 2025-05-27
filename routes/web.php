<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/send-test', function () {
    Mail::raw('Це тестовий лист без шаблону.', function ($message) {
        $message->to('sergeevvalik@gmail.com')
                ->subject('Test Email');
    });

    return 'Лист надіслано!';
});

Route::redirect('/admin/login', '/login');


require __DIR__.'/auth.php';
