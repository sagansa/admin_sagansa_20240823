<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return redirect('https://www.sagansa.id/admin');
// });

// Route::get('/', function () {
//     return view('home');
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/profile', 'profile')->name('profile');
Route::view('/contact', 'contact')->name('contact');
Route::get(
    '/login',
    function () {
        return redirect('https://www.sagansa.id/admin/login');
    }
);
Route::get(
    '/register',
    function () {
        return redirect('https://www.sagansa.id/admin/register');
    }
);

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});

Route::get('/admin/login', function () {
    return redirect()->route('filament.auth.login');
})->name('login');

Route::get('/admin/register', function () {
    return redirect()->route('filament.auth.register');
})->name('register');
