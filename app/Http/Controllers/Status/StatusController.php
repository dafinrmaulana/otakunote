<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\Controller;
use App\Http\Requests\Status\StatusStoreRequest;
use App\Http\Requests\Status\StatusUpdateRequest;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class StatusController extends Controller
{
  public function index(): Response
  {
    $statuses = Status::orderBy('is_pinned', 'desc')
      ->where('user_id', Auth::user()->id)
      ->with('series', function ($query) {
        $query->select('id', 'title', 'slug', 'status_id');
      })
      ->orderBy('name', 'asc')
      ->paginate(36);
    return inertia('Status/Index', compact('statuses'));
  }


  public function show(Status $status): Response
  {
    if ($status->user_id !== Auth::id()) {
      abort(403);
    }
    $status->load('series', 'series.status', 'series.latestEpisode');
    return inertia('Status/Show', compact('status'));
  }

  public function store(StatusStoreRequest $request): RedirectResponse
  {
    try {
      $status = Status::create($request->data());
      return back()->with('success', "Status {$status->name} created successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function update(StatusUpdateRequest $request, Status $status): RedirectResponse
  {
    try {
      $status->update($request->data());
      return back()->with('success', "Status {$status->name} updated successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }

  public function destroy(Status $status): RedirectResponse
  {
    try {
      $status->delete();
      return back()->with('success', "Status {$status->name} deleted successfully");
    } catch (\Exception $e) {
      return back()->with("failed", "Oops, something went wrong");
    }
  }
}
