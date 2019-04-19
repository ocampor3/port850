<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\AppAccess;
use App\Models\ApiAuthSessions;

class ApiMiddleware
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
        $now = date('Y-m-d H:i:s');
        $apiSession = ApiAuthSessions::where('apiAuthSessionsUsername',$request->input('username'))
                    ->where('apiAuthSessionsToken',$request->input('token'))
                    ->where('expirationDate', '>', $now)                    
                    ->first();

        if(empty($apiSession)) {
            //return "Authentication Error";
            echo $request->input('token');
            return response()->json([['ErrorMessage' => 'Authentication Failed.']]);
        } else {
            return $next($request);
        }
    }
}
