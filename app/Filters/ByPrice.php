<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByPrice
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, \Closure $next)
    {
        return $next($builder)
            ->when($this->request->has('price'),
                fn($query) => $query->where('price', '<=', $this->request['price'])
            );
    }
}
