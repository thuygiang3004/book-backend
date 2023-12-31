<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static create(array $input)
 * @method static where(string $string, string $id)
 * @method static find(string $id)
 */
class Listing extends Model
{
    protected $fillable = ['title', 'book_id', 'price', 'status'];
    protected $table='listings';
    use HasFactory;
}
