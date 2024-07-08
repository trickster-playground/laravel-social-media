<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'body' => $this->body,
      'created_at' => $this->created_at->format('Y-m-d - H:i'),
      'updated_at' => $this->updated_at->format('Y-m-d - H:i'),
      'user' => new UserResource($this->user),
      'group' => $this->group,
      'number_of_reactions' => $this->reactions_count,
      'attachments' => PostAttachmentResource::collection($this->attachments),
      'current_user_has_reaction' => $this->reactions->count() > 0,
    ];
  }
}
