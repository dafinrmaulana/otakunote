<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Episode\EpisodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Series\SeriesController;
use App\Http\Controllers\Status\StatusController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
  return inertia('Auth/Login');
});

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // status
  Route::get('/status', [StatusController::class, 'index'])->name('status.index');
  Route::post('/status', [StatusController::class, 'store'])->name('status.store');
  Route::get('/status/{status:slug}', [StatusController::class, 'show'])->name('status.show');
  Route::put('/status/{status}', [StatusController::class, 'update'])->name('status.update');
  Route::delete('/status/{status}', [StatusController::class, 'destroy'])->name('status.destroy');

  // genre
  // Route::get('/genre', [GenreController::class, 'index'])->name('genre.index');
  // Route::post('/genre', [GenreController::class, 'store'])->name('genre.store');
  // Route::put('/genre/{genre}', [GenreController::class, 'update'])->name('genre.update');
  // Route::delete('/genre/{genre}', [GenreController::class, 'destroy'])->name('genre.destroy');

  // category
  Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
  Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
  Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
  Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
  Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

  // series
  Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
  Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
  Route::put('/series/{series}/update', [SeriesController::class, 'update'])->name('series.update');
  Route::delete('/series/{series}/delete', [SeriesController::class, 'destroy'])->name('series.destroy');
  Route::put('/series/{series}/delete-thumbnail', [SeriesController::class, 'removeThumbnail'])->name('series.thumbnail.destroy');
  Route::get('/series/{series:slug}', [SeriesController::class, 'show'])->name('series.show');

  // image
  Route::get('assets/{folder}/{subfolder}/{subsubfolder}/{filename}', [ImageController::class, 'show'])->name('image.show');

  // episode
  Route::get('/dashboard', [EpisodeController::class, 'index'])->name('episode.index');
  Route::post('/dashboard', [EpisodeController::class, 'store'])->name('episode.store');
  Route::put('/dashboard/{episode}', [EpisodeController::class, 'update'])->name('episode.update');
  Route::delete('/dashboard/{episode}', [EpisodeController::class, 'destroy'])->name('episode.destroy');

  // cors api
  Route::get('/proxy/recomendations/manga', function () {
    $title = request('q');

    $response = Http::get("https://api.jikan.moe/v4/manga", [
      'q' => $title
    ]);

    return $response->json();
  });
  Route::get('/proxy/recomendations/anime', function () {
    $title = request('q');

    $response = Http::get("https://api.jikan.moe/v4/anime", [
      'q' => $title
    ]);

    return $response->json();
  });

  // search
  Route::get('/search', [SearchController::class, 'index'])->name('search');
});

require __DIR__ . '/auth.php';
