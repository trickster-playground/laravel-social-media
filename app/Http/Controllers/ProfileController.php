<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
  /**
   * Display the user's profile form.
   */

  public function index(User $user)
  {
    return Inertia::render('Profile/View', [
      'mustVerifyEmail' => $user instanceof MustVerifyEmail,
      'status' => session('status'),
      'success' => session('success'),
      'user' => UserResource::make($user),
      'message' => session('message'),
    ]);
  }

  public function edit(Request $request): Response
  {
    return Inertia::render('Profile/Edit', [
      'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
      'status' => session('status'),
    ]);
  }

  /**
   * Update the user's profile information.
   */
  public function update(ProfileUpdateRequest $request): RedirectResponse
  {
    $request->user()->fill($request->validated());

    if ($request->user()->isDirty('email')) {
      $request->user()->email_verified_at = null;
    }

    $request->user()->save();

    return to_route('worlds', $request->user())->with('message', 'Profile updated successfully');
  }

  /**
   * Delete the user's account.
   */
  public function destroy(Request $request): RedirectResponse
  {
    $request->validate([
      'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
  }

  public function updateImages(Request $request)
  {
    $data = $request->validate([
      'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = $request->user();

    $message = '';
    if ($data['cover']) {
      $path = $data['cover']->store('cover-images/' . 'user-' . $user->id, 'public');
      if ($user->cover_path) {
        Storage::delete('public/' . $user->cover_path);
      }
      $user->update(['cover_path' => $path]);
      $message = 'Cover updated successfully';
    }

    if ($data['avatar']) {
      $path = $data['avatar']->store('avatar-images/' . 'user-' . $user->id, 'public');
      if ($user->avatar_path) {
        Storage::delete('public/' . $user->avatar_path);
      }
      $user->update(['avatar_path' => $path]);
      $message = 'Avatar updated successfully';
    }

    return back()->with('message', $message);
  }
}
