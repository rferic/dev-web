<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use Illuminate\Support\Facades\Hash;

class CacheController extends Controller
{    
    public function forget (Request $request)
    {
        $url = $request->fullUrl();
        Cache::forget(Hash::make($url));
        return response()->json(['response' => true]);
    }
    
    public function flush ()
    {
        Cache::flush();
        return response()->json(['response' => true]);
    }
}
