<?php

namespace App\Listeners\Api;

use App\Events\Api\OnNewsCreated;
use App\User;
use App\Jobs\SendPushToUser;
use App\Service\Push\Contracts\PushMessageContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPushOnNewsCreated implements ShouldQueue
{
    use InteractsWithQueue;

    private $message;

    /**
     * @param PushMessageContract $message
     */
    public function __construct(PushMessageContract $message)
    {
        $this->message = $message;
    }

    /**
     * Handle the event.
     *
     * @param  OnNewsCreated $event
     * @return void
     */
    public function handle(OnNewsCreated $event)
    {
        $payload = [
            'aps' => [
                'alert' => [
                    'title' => "Новость",
                    'body' => $event->getNews()->title,
                ],
                'type' => 'newsNotification',
                'sound' => 'default',
            ],
        ];
        $this->message->setMessage("Новость " . $event->getNews()->title);
        $this->message->setPayload($payload);
        foreach (User::where('status', User::STATUS_ACTIVE)->get() as $user) {
            SendPushToUser::dispatch($user, $this->message);
        }
    }
}
