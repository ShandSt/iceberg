<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Service\OneC\Server\OneCServer;

class StatusController extends Controller
{
    public function status(OneCServer $service)
    {
        return response()->json($service->checkServerStatus());
    }
}
