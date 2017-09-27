<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use Illuminate\Support\Facades\Hash;

class CacheController extends Controller
{    
    public function forget (Request $request)
    {
        Cache::forget($request->input('url'));
        return response()->json(['response' => true]);
    }
    
    public function flush ()
    {
        Cache::flush();
        return response()->json(['response' => true]);
    }
}
