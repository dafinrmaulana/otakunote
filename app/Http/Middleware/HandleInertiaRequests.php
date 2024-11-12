<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that is loaded on the first page visit.
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determine the current asset version.
   */
  public function version(Request $request): ?string
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @return array<string, mixed>
   */
  public function share(Request $request): array
  {
    return [
      ...parent::share($request),
      'auth' => [
        'user' => $request->user(),
      ],
      'ziggy' => fn() => [
        ...(new Ziggy)->toArray(),
        'location' => $request->url(),
      ],
      'session' => [
        'success' => fn() => $request->session()->get('success'),
        'info' => fn() => $request->session()->get('info'),
        'failed' => fn() => $request->session()->get('failed'),
        'warning' => fn() => $request->session()->get('warning'),
      ],
      'links' => [
        'pinned_series' => Auth::check() ? Series::where('user_id', Auth::user()->id)
          ->where('is_pinned', 1)->select('title', 'slug')
          ->get() : [],
        'pinned_categories' => Auth::check() ? Category::where('user_id', Auth::user()->id)
          ->where('is_pinned', 1)->select('name', 'slug', 'color')
          ->get() : [],
      ]
    ];
  }
}
