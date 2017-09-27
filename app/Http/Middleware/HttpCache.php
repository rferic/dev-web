<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use Illuminate\Support\Facades\Hash;

class HttpCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $time (minutes)
     * @return mixed
     */
    public function handle($request, Closure $next, $time = 60)
    {
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            $key = Hash::make($request->fullUrl());

            if (Cache::has($key)) {
                $content = Cache::get($key);
                return response($content);
            }
        }
        
        $response = $next($request);
        
        if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            Cache::put($key, $response->getContent(), (float)$time);
        }
        
        return $response;
    }
}
