<?php

namespace App\Models;

use App\Traits\Commentable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Listing extends Model
{
    use Searchable;
    use Commentable;
    protected $fillable = ['title', 'price', 'status', 'images'];

    //    protected $table='listings';
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)->withPivot('order');
    }

    public function scopeIsNew(): Builder
    {
        return $this->where('status', 'new');
    }
}
