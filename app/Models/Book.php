<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail($id)
 * @method static create(array $input)
 * @method static where(string $string, string $id)
 * @method static find(string $id)
 */
class Book extends Model
{
    protected $fillable = ['title', 'author', 'publisher'];

    //    protected $table = 'books';
    use HasFactory;

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
