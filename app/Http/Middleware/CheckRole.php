<?php
// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            abort(403, 'Zugriff verweigert');
        }
        
        return $next($request);
    }
}