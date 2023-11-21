<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static findOrFail($id)
 * @method static create(array $input)
 * @method static where(string $string, string $id)
 * @method static find(string $id)
 */
class Listing extends Model
{
    protected $fillable = ['title', 'book_id', 'price', 'status', 'images'];

    //    protected $table='listings';
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
