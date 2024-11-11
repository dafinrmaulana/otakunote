<?php

namespace App\Http\Controllers\Episode;

use App\Http\Controllers\Controller;
use App\Http\Requests\Episode\EpisodeStoreRequest;
use App\Http\Requests\Episode\EpisodeUpdateRequest;
use App\Models\Episode;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\ResponseFactory;

class EpisodeController extends Controller
{
  public function index(): Response|ResponseFactory
  {
    $episodes = Episode::where('user_id', Auth::user()->id)
      ->with([
        'series' => function ($query) {
          $query->select('id', 'title', 'thumbnail', 'slug', 'rating', 'is_pinned', 'media', 'category_id', 'status_id');
        },
        'series.category' => function ($query) {
          $query->select('id', 'name');
        },
        'series.status' => function ($query) {
          $query->select('id', 'name', 'color');
        },
        'series.latestEpisode' => function ($query) {
          $query->select('number', 'series_id');
        }
      ])
      ->whereIn('number', function ($query) {
        $query->selectRaw('MAX(number)')
          ->from('episodes as e2')
          ->whereColumn('e2.series_id', 'episodes.series_id')
          ->groupBy('e2.series_id');
      })
      ->latest()
      ->paginate(20);

    $series = Series::select(['id', 'title'])->with('latestEpisode')->get();

    return inertia('Dashboard', compact('episodes', 'series'));
  }

  public function store(EpisodeStoreRequest $request)
  {
    try {
      $episode = Episode::create($request->data());
      return back()->with('success', "New episode has added to {$episode->series->title}");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function update(EpisodeUpdateRequest $request, Episode $episode)
  {
    try {
      $episode->update($request->data());
      return back()->with('success', "Episode {$episode->series->title} updated successfully to {$episode->number}");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function destroy(Episode $episode)
  {
    try {
      $episode->delete();
      return back()->with("success", "Episode {$episode->series->title} deleted successfully");
    } catch (\Throwable $th) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }
}
