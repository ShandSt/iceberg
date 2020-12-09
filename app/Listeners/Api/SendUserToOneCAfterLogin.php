<?php

namespace App\Listeners\Api;

use App\Events\Api\UserCreated;
use App\Service\OneC\Actions\UserAction;
use App\Service\OneC\Contracts\OneCClientContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserToOneCAfterLogin implements ShouldQueue
{
    private $service;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserAction $user_action)
    {
        $this->service = $user_action;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->isNewUser() or empty($event->getUser()->guid)) {
            $this->service->addUser($event->getUser()->toArray());
        } else {
            $this->service->updateUser($event->getUser()->id, $event->getUser()->toArray());
        }
    }
}
