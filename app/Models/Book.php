<?php

namespace App\Models;

use App\Traits\Commentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Book extends Model
{
    protected $fillable = ['title', 'author', 'publisher'];

    //    protected $table = 'books';
    use HasFactory;
    use Commentable;

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class);
    }
}
