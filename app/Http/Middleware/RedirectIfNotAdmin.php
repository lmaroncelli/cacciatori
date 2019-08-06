<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class RedirectIfNotAdmin
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
        $user = Auth::user();
        
        if(!$user || $user->ruolo != 'admin')
            // OPPURE redirect alla home !!!
            //abort('403','Impossibile accedere. Privilegi insufficienti!');
            return redirect()->route('login')->with('status', 'Impossibile accedere. Privilegi insufficienti!');;
        return $next($request);
    }
}
