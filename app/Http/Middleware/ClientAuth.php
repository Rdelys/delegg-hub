<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('client_logged')) {
            return redirect()->route('client.login')
                ->with('error', 'Veuillez vous connecter');
        }

        return $next($request);
    }
}
