<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware('auth')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
  Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
  Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

  Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])->name('posts.download');
});


Route::middleware(['auth'])->group(function () {
  Route::post('/profile/update-images', [ProfileController::class, 'updateImages'])->name('profile.update-images');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/worlds/{user:username}', [ProfileController::class, 'index'])->name('worlds');


require __DIR__ . '/auth.php';
