<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StorePostRequest $request)
  {
    $data = $request->validated();
    $user = $request->user();

    DB::beginTransaction();
    $allFilePaths = [];
    try {
      $post = Post::create($data);

      //Store File

      $files = $data['attachments'];
      foreach ($files as $file) {
        $path = $file->store('attachments/' . $post->id, 'public');
        $allFilePaths[] = $path;
        PostAttachment::create([
          'post_id' => $post->id,
          'name' => $file->getClientOriginalName(),
          'path' => $path,
          'mime' => $file->getMimeType(),
          'size' => $file->getSize(),
          'created_by' => $user->id,
        ]);
      }

      DB::commit();
    } catch (\Exception $e) {
      foreach ($allFilePaths as $path) {
        Storage::disk('public')->delete($path);
      }
      DB::rollBack();
      return response('Failed to create post', 500);
    }


    return back();
  }

  /**
   * Display the specified resource.
   */
  public function show(Post $post)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Post $post)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdatePostRequest $request, Post $post)
  {

    $user = $request->user();

    DB::beginTransaction();
    $allFilePaths = [];
    try {
      $data = $request->validated();
      $post->update($data);

      $deletedIds = $data['deletedIds'] ?? [];

      $attachments = PostAttachment::query()
        ->where('post_id', $post->id)
        ->whereIn('id', $deletedIds)
        ->get();

      foreach ($attachments as $attachment) {
        $attachment->delete();
      }

      //Store File

      $files = $data['attachments'] ?? [];
      foreach ($files as $file) {
        $path = $file->store('attachments/' . $post->id, 'public');
        $allFilePaths[] = $path;
        PostAttachment::create([
          'post_id' => $post->id,
          'name' => $file->getClientOriginalName(),
          'path' => $path,
          'mime' => $file->getMimeType(),
          'size' => $file->getSize(),
          'created_by' => $user->id,
        ]);
      }

      DB::commit();
    } catch (\Exception $e) {
      foreach ($allFilePaths as $path) {
        Storage::disk('public')->delete($path);
      }
      DB::rollBack();
      return response('Failed to create post', 500);
    }


    return back();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Post $post)
  {
    // TODO:
    $id = Auth::id();
    if ($id !== $post->user_id) {
      return response('Unauthorized', 403);
    }
    $post->delete();
    return back();
  }

  public function downloadAttachment(PostAttachment $attachment)
  {

    $customName = $attachment->name;

    return response()->download(Storage::disk('public')->path($attachment->path), $customName);
  }
}
