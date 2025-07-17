<?php

namespace App\Http\Middleware;

use App\Exceptions\FrontException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws FrontException
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();
        if (!($user instanceof User)) {
            throw new FrontException('You\'re not signed in.');
        }

        if (!$user->hasPermission($permissions)) {
            throw new FrontException('You do not have permission.');
        }

        return $next($request);
    }
}
