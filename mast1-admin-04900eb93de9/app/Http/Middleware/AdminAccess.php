<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if (session()->get('type') == 1){
            // redirected to dashboard.
        }else{
            return redirect()->intended('/');
        }
        
        return $next($request);
    }
}
