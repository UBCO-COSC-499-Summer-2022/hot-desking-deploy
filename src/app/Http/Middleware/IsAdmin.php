<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    private function checkIfUserIsAdmin($user)
    {
        return ($user->is_admin == 1);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->checkIfUserIsAdmin(User::find(Auth::id()))) {
            return redirect()->route('home');
        }
        return $next($request);
    }
}