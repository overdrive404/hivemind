<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $table = 'post_images';

    protected $fillable = ['post_id', 'path'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

}
