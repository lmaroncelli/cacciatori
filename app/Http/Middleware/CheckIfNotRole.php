<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfNotRole
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

        $role = $request->role;

        if($user && !is_null($role) && $user->ruolo != $role)
          {
            return $next($request);
          }
        else 
          {
          return back()->with('status', 'Non hai i permessi !!');
          }
    }
}
