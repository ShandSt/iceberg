<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Davibennun\LaravelPushNotification\PushNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PushController extends Controller
{
    /**
     * @var array
     */
    private $payload;

    public function __construct()
    {
        $this->payload = [
            'aps' => [
                'alert' => [
                    'title' => "Тайтл",
                    'body' => "Боди",
                ],
                'type' => 'testNotification',
                'sound' => 'default',
            ],
        ];
    }

    public function create(): View
    {
        return view('push.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate(
            $request,
            [
                'phone' => 'required_without:token',
                'token' => 'required_without:phone',
                'message' => 'required',
            ]
        );

        $this->payload['aps']['alert']['title'] = $request->input('title', 'Тайтл');
        $this->payload['aps']['alert']['body'] = $request->input('body', 'Боди');

        $answer = [];

        if ($request->input('phone')) {
            $user = User::where('phone', $request->input('phone'))->firstOrFail();
            $answer = $this->sendPushByUser($user, $request->input('message'));
        } elseif ($request->input('token')) {
            $answer = $this->sendPushByToken($request->input('token'), $request->input('message'));
        }

        return redirect('sendpushservice')->with('pushresult', $answer);
    }

    private function sendPushByUser(User $user, string $message): array
    {
        $result = \PushNotification::app('android')->to($user->devices[0]->token)->send($message, $this->payload);

        return $result->adapter->getResponse()->getParsedResponses();
    }

    private function sendPushByToken(string $token, string $message): array
    {
        $result = \PushNotification::app('android')->to($token)->send($message, $this->payload);

        return $result->adapter->getResponse()->getParsedResponses();
    }
}
