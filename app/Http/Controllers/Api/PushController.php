<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use App\User;
use Illuminate\Http\Request;
use Laravel\Telescope\Telescope;

class PushController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->header('token') !== 'flTf9vXg5ymuyyxSXAGbGnUZrjLwMnIn') {
            abort(401, 'Wrong token');
        }

        Telescope::tag(
            function () {
                return ['PushFrom1CSending'];
            }
        );

        $attributes = $request->validate(
            [
                'user' => 'required_without:users|exists:users,id',
                'type' => 'required',
                'title' => 'required',
                'message' => 'required',
            ]
        );

        if (!$device = $this->getUserDevice((int)$attributes['user'])) {
            return response()->json(['message' => __('User don\'t have any device')], 422);
        }

        if ($device->os === 'android') {
            $answer = $this->sendPushAndroid(
                $device->token,
                $request->input('type'),
                $request->input('title'),
                $request->input('message')
            );

            return response()->json(
                [
                    'message' => __('Push message was sent'),
                    'answer' => $answer,
                ]
            );
        }

        return response()->json(['message' => __('Not supported user device OS')], 422);
    }

    private function getUserDevice(int $userId): ?UserDevice
    {
        /** @var User $user */
        $user = User::find($userId);

        /** @var UserDevice $device */
        $device = $user->devices()->first();

        return $device;
    }

    private function sendPushAndroid(string $token, string $type, string $title, string $message)
    {
        $payload = [
            'aps' => [
                'alert' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'type' => $type,
                'sound' => 'default',
            ],
        ];

        $result = \PushNotification::app('android')->to($token)->send($message, $payload);

        return $result->adapter->getResponse()->getParsedResponses();
    }
}
