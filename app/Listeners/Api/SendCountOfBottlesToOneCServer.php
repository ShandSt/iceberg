<?php

namespace App\Listeners\Api;

use App\Events\Api\OnBottlesUpdated;
use App\Service\OneC\Actions\UserAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCountOfBottlesToOneCServer implements ShouldQueue
{
    private $action;

    /**
     * SendCountOfBottlesToOneCServer constructor.
     * @param UserAction $action
     */
    public function __construct(UserAction $action)
    {
        $this->action = $action;
    }

    /**
     * Handle the event.
     *
     * @param  OnBottlesUpdated $event
     * @return void
     */
    public function handle(OnBottlesUpdated $event)
    {
        $this->action->setBottles($event->getUser()->id, [
            'guess' => $event->getUser()->bottles,
        ]);
    }
}
