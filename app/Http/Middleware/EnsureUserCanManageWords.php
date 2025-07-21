<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManageWords
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to manage words.');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if user has permission to manage words
        if (!$user || !$user->canManageWords()) {
            return redirect()->route('words.index')
                ->with('error', 'You do not have permission to manage words.');
        }

        return $next($request);
    }
}