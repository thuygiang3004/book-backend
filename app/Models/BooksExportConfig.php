<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksExportConfig extends Model
{
    use HasFactory;

    protected $fillable = ['config->columnsOrder'];

    protected $attributes = [
        'config' => '{}'
    ];
    protected $casts = ['config' => AsArrayObject::class];
}
