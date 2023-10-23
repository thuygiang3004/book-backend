<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = ['title, book_id, price, status'];
    protected $table='listings';
    use HasFactory;
}
