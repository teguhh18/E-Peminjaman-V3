<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class IsAuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            abort(403);
            // return redirect()->route('login');
        }
        return $next($request);
    }
}
