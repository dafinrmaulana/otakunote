<?php

namespace App\Http\Controllers\Series;

use App\Http\Controllers\Controller;
use App\Http\Requests\Series\SeriesStoreRequest;
use App\Http\Requests\Series\SeriesUpdateRequest;
use App\Models\Category;
use App\Models\Series;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class SeriesController extends Controller
{
  public function index(): Response
  {
    $categories = Category::where('user_id', Auth::user()->id)
      ->get(["id", "name"]);
    $statuses = Status::where('user_id', Auth::user()->id)
      ->get(["id", "name"]);
    $series = Series::orderBy('is_pinned', 'desc')
      ->orderBy('title', 'asc')
      ->where('user_id', Auth::user()->id)
      ->with([
        'status' => function ($query) {
          $query->select('id', 'name', 'color');
        },
        'category' => function ($query) {
          $query->select('id', 'name');
        },
        'latestEpisode' => function ($query) {
          $query->select('id', 'number', 'series_id');
        }
      ])
      ->paginate(36);

    return inertia('Series/Index', [
      'categories' => $categories,
      'statuses' => $statuses,
      'series' => $series
    ]);
  }

  public function store(SeriesStoreRequest $request)
  {
    try {
      $series = Series::create($request->data());
      return back()->with('success', "Series {$series->name} created successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function show(Series $series)
  {
    if ($series->user_id !== Auth::id()) {
      abort(403);
    }
    $series->load([
      'status' => function ($query) {
        $query->select('id', 'name', 'color');
      },
      'category' => function ($query) {
        $query->select('id', 'name');
      },
      'episodes' => function ($query) {
        $query->orderBy('number', 'desc');
      },
      'episodes.series',
      'latestEpisode'
    ]);
    return inertia("Series/Show", compact('series'));
  }

  public function update(SeriesUpdateRequest $request, Series $series)
  {
    try {
      $series->update($request->data());
      return back()->with('success', "Series {$series->name} updated successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function destroy(Series $series)
  {
    try {
      if ($series->thumbnail) {
        Storage::disk('local')->delete($series->thumbnail);
      }
      $series->delete();
      return back()->with('success', "Series {$series->name} deleted successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function removeThumbnail(Series $series)
  {
    try {
      if ($series->thumbnail) {
        Storage::disk('local')->delete($series->thumbnail);
        $series->thumbnail = null;
        $series->save();
      }
      return back()->with('success', "Series {$series->title} thumbnail removed successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }
}
