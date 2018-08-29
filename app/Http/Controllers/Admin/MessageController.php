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

    public function getter ()
    {
    	return Response::json([ 'messages' => Message::get() ]);
    }

    public function getCountPendings ()
    {
        return Response::json([ 'pendings' => Message::where('status', 'new') ]);
    }
}
