<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (auth()->user()) {
        //     // return new Response('Forbidden', 403);
        //     return response()->json(['status' => 201, 'meassge' => 'I am in Cart']);
        // } else {
        //     return response()->json(['status' => 201, 'meassge' => 'Else']);
        // }
        return $next($request);
    }
}
