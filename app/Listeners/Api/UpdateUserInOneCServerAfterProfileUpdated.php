<?php

namespace App\Listeners\Api;

use App\Events\Api\OnUserProfileUpdated;
use App\Service\OneC\Actions\UserAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserInOneCServerAfterProfileUpdated
{


    private $service;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserAction $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  OnUserProfileUpdated  $event
     * @return void
     */
    public function handle(OnUserProfileUpdated $event)
    {
        if ($event->getUser()->guid) {
            $this->service->updateUser($event->getUser()->id, $event->getUser()->toArray());
        }
    }
}
