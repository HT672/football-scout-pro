<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckScoutRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('home')
                ->with('error', 'You need to log in to access this resource.');
        }
        
        $user = auth()->user();
        
        // Check if user has the role property and if it's scout or admin
        if (!($user instanceof User) || !in_array($user->role, ['scout', 'admin'])) {
            return redirect()->route('home')
                ->with('error', 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}