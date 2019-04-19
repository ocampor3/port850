<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Session;

class VisitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) 
        {
            return redirect()->guest('auth/login');
        }
        else
        {
            $user = Auth::user();   
                        
            if($user->UserGroup == 'Visitor')
            {
                return Redirect::to('401');
            }    
            else
                return $next($request);  
        }
    }
}
