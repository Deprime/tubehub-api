<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next, ...$guards)
  {
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
      if ($guard === "sanctum") {
        if (Auth::guard($guard)->check()) {
          return $next($request);
        }
        else {
          return response()->json([Response::HTTP_FORBIDDEN . ": HTTP_FORBIDDEN"], Response::HTTP_FORBIDDEN);
        }
      }
    }
    return $next($request);
  }
}
