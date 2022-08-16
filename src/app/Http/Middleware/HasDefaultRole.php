<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HasDefaultRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    private function hasDefaultRole($user) 
    {
        return ($user->role_id == 1);
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->hasDefaultRole(User::find(Auth::id()))) {
            Session::flash('message', 'You currently have no role defined you must select a role before you can use the site'); 
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('profile');
        }
        return $next($request);
    }
}
