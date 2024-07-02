<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use HasFactory;
    CONST UPDATED_AT = 'created_at';

    protected $fillable = [
        'post_id', 'name', 'path', 'mime', 'size', 'created_by'
    ];
}
