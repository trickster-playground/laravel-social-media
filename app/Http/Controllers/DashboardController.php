<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function index()
  {
    $userId = Auth::id();
    $posts = Post::query()->withCount('reactions')->with(['reactions' => function ($query) use ($userId) {
      $query->where('user_id', $userId);
    }])->latest()->paginate(10);
    return Inertia::render('Dashboard', [
      'posts' => PostResource::collection($posts),
    ]);
  }
}
