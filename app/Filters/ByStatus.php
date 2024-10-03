<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByStatus
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(Builder $builder, \Closure $next)
    {
//        dd($this->request->has('isNew'));
        return $next($builder)
            ->when($this->request->has('isNew'),
                fn($query) => $query->isNew()
            );
    }
}
