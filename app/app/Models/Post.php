<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostImage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];


public function images()
{
    return $this->hasMany(PostImage::class);
}

public function user(){// relationship to user
    return $this->belongsTo(User::class);
}

}
