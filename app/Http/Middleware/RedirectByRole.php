<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->detectRole() === 'admin') {
            return redirect('admin/users');
        }

        return redirect('admin/handling/categories/params/choose');
    }

    /**
     * @return string
     */
    private function detectRole(): string
    {
        return Auth::user()->permissions['platform.systems.roles'] == 1
            ? 'admin'
            : 'manager';
    }
}
