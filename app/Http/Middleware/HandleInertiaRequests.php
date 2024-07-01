<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

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
  public function version(Request $request): string|null
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
        'user' => $request->user() ? new UserResource($request->user()) : null,
      ],
      'ziggy' => fn () => [
        ...(new Ziggy)->toArray(),
        'location' => $request->url(),
      ],
      ...parent::share($request), [
        'flash' => [
          'message' => fn () => $request->session()->get('message')
        ],
      ],
    ];
  }
}
