<?php

namespace App\Http\Controllers\Genre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\GenreStoreRequest;
use App\Http\Requests\Genre\GenreUpdateRequest;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class GenreController extends Controller
{
  public function index(): Response
  {
    $genres = Genre::orderBy('is_pinned', 'desc')
      ->orderBy('name', 'asc')
      ->where('user_id', Auth::user()->id)
      ->paginate(16);
    return inertia('Genre/Index', compact('genres'));
  }

  public function store(GenreStoreRequest $request): RedirectResponse
  {
    try {
      $genre = Genre::create($request->data());
      return back()->with('success', "Genre {$genre->name} created successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function update(GenreUpdateRequest $request, Genre $genre): RedirectResponse
  {
    try {
      $genre->update($request->data());
      return back()->with('success', "Genre {$genre->name} updated successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function destroy(Genre $genre): RedirectResponse
  {
    try {
      $genre->delete();
      return back()->with("success", "Genre {$genre->name} deleted successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }
}
