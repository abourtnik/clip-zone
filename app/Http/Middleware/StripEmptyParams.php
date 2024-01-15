<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class StripEmptyParams
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $query = $request->query();

        if (!$query) {
            return $next($request);
        }

        $cleanQuery = array_filter($query, fn($value) => !is_null($value));

        if (count($query) === count($cleanQuery)) {
            return $next($request);
        }

        return redirect()->route($request->route()->getName(), $cleanQuery);
    }
}
