<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use App\Http\Requests\StorePostRequest;

class UpdatePostRequest extends StorePostRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    // Todo maybe change later
    $post = $this->route('post');

    return $post->user_id == Auth::id();
  }

  public function rules(): array
  {
    return array_merge(parent::rules(), [
      'deletedIds' => 'array',
      'deletedIds.*' => 'exists:post_attachments,id',
    ]);
  }
}
