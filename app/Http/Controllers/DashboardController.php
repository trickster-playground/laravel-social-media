<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class DashboardController extends Controller
{
  public function index()
  {
    $posts = Post::query()->latest()->paginate(10);
    return Inertia::render('Dashboard', [
      'posts' => PostResource::collection($posts),
    ]);
  }
}
