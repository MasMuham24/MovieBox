<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieListController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [MovieListController::class, 'dashboard'])->name('dashboard');
    Route::get('/search', [MovieListController::class, 'search'])->name('search');
    Route::get('/movie/{movieId}', [MovieListController::class, 'show'])->name('movie.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/favorites/{movieId}/toggle', [MovieListController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::post('/watchlist/{movieId}/toggle', [MovieListController::class, 'toggleWatchlist'])->name('watchlist.toggle');
    Route::get('/favorites', [MovieListController::class, 'favorites'])->name('favorites');
    Route::get('/watchlist', [MovieListController::class, 'watchlist'])->name('watchlist');
    Route::get('/test-tmdb', [MovieController::class, 'testTmdb']);
});