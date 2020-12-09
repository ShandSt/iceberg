<?php

namespace App\Listeners\Api;

use App\Events\Api\OnConsumptionUpdate;
use App\Service\OneC\Actions\UserAction;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConsumptionToOneC implements ShouldQueue
{
    private $action;

    /**
     * SendConsumptionToOneC constructor.
     * @param UserAction $action
     */
    public function __construct(UserAction $action)
    {
        $this->action = $action;
    }

    /**
     * Handle the event.
     *
     * @param  OnConsumptionUpdate  $event
     * @return void
     */
    public function handle(OnConsumptionUpdate $event)
    {
        $response = $this->action->setConsumption($event->getConsumption(), $event->getUser()->id);
    }
}
