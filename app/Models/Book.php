<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Book extends Model
{
    protected $fillable = ['title', 'author', 'publisher'];

    //    protected $table = 'books';
    use HasFactory;

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
