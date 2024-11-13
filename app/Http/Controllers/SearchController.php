<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Series;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $by = $request->query('by');

    if ($by === 'all') {
      $series = Series::where('user_id', Auth::user()->id)
        ->where(function ($query) use ($request) {
          $query->Where('title', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('slug', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('author', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('studio', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('media', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('source_url', 'LIKE', "%{$request->query('query')}%");
        })
        ->get(['title', 'slug', 'rating', 'thumbnail', 'user_id']);

      $categories = Category::where('user_id', Auth::user()->id)
        ->where(function ($query) use ($request) {
          $query->where('name', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('slug', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('description', 'LIKE', "%{$request->query('query')}%");
        })
        ->with('series', function ($query) {
          $query->select('id', 'title', 'slug', 'category_id');
        })
        ->get(['id', 'name', 'slug', 'color', 'user_id']);

      $statuses = Status::where('user_id', Auth::user()->id)
        ->where(function ($query) use ($request) {
          $query->where('name', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('description', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('slug', 'LIKE', "%{$request->query('query')}%");
        })
        ->with('series', function ($query) {
          $query->select('id', 'title', 'slug', 'status_id');
        })
        ->get(['id', 'name', 'slug', 'color', 'user_id']);

      return response()->json([
        'series' => $series,
        'categories' => $categories,
        'statuses' => $statuses,
      ], 200);
    } else if ($by === 'series') {
      $series = Series::where('user_id', Auth::user()->id)
        ->where(function ($query) use ($request) {
          $query->Where('title', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('slug', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('author', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('studio', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('media', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('source_url', 'LIKE', "%{$request->query('query')}%");
        })
        ->get(['title', 'slug', 'rating', 'thumbnail', 'user_id']);
      return response()->json([
        'series' => $series,
      ], 200);
    } else if ($by === 'type') {
      $categories = Category::where('user_id', Auth::user()->id)
        ->where(function ($query) use ($request) {
          $query->where('name', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('slug', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('description', 'LIKE', "%{$request->query('query')}%");
        })
        ->with('series', function ($query) {
          $query->select('id', 'title', 'slug', 'category_id');
        })
        ->get(['id', 'name', 'slug', 'color', 'user_id']);

      return response()->json([
        'categories' => $categories,
      ], 200);
    } else if ($by === 'statuses') {
      $statuses = Status::where('user_id', Auth::user()->id)
        ->where(function ($query) use ($request) {
          $query->where('name', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('description', 'LIKE', "%{$request->query('query')}%")
            ->orWhere('slug', 'LIKE', "%{$request->query('query')}%");
        })
        ->with('series', function ($query) {
          $query->select('id', 'title', 'slug', 'status_id');
        })
        ->get(['id', 'name', 'slug', 'color', 'user_id']);

      return response()->json([
        'statuses' => $statuses,
      ], 200);
    }
  }
}
