<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use App\Models\Core\Message;

class MessageController extends Controller
{
    public function index ()
    {
        
    }

    public function getCountLastMessages ()
    {
        return Response::json([ 'count' => Message::where('created_at', '>', Input::get('timeSince'))->count() ]);
    }

    public function getCountPendings ()
    {
        return Response::json([ 'count' => Message::where('status', 'pending')->count() ]);
    }
}
