<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response as HttpResponse;

class Limit
{
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') == 'local') {
            //$meth = ['103.140.189.172', '103.140.189.174', '103.140.189.173'];
            $meth = ['103.140.189.172'];
            if (in_array(\Request::ip(), $meth)) {
                return $next($request);
            } else {
                //return abort(404);
                return redirect('not-found');
            }
        } else {
            //return abort(404);
            return redirect('not-found');
        }
    }
}
