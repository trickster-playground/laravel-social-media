<?php

namespace App\Models;

use App\Models\PostAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $guarded = ['id'];


  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function group(): BelongsTo
  {
    return $this->belongsTo(Group::class);
  }
  public function attachments(): HasMany
  {
    return $this->hasMany(PostAttachment::class)->latest();
  }
}
