<?php

namespace App\Http\Middleware;

use Closure;
use App\UserLogin;
use Session;

class CheckRegisterUser
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

        $url         = url('/');
        $url_current = url()->current();
        $use         = Session::get('user_idSession');
        
        if(($url_current !== $url) && (($use == "") || ($use == null))){
            return \redirect('/');
        }
        
        return $next($request);
    }
}
