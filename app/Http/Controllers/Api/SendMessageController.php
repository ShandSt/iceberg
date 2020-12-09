<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FeedbackRequest;
use App\Http\Requests\Api\ReviseRequest;
use App\Mail\Feedback;
use App\Mail\OrderRevise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendMessageController extends Controller
{
    public function revise(ReviseRequest $request)
    {
        Mail::to('feedback@iceberg-aqua.ru')->send(new OrderRevise($request, Auth::user()));
    }

    public function feedback(FeedbackRequest $request)
    {
        Mail::to('feedback@iceberg-aqua.ru')->send(new Feedback(Auth::user(), $request->get('message'), $request->get('email')));
    }
}
