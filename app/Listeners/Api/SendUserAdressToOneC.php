<?php

namespace App\Listeners;

use App\Events\Api\OnUserAdressAdded;
use App\Models\Address;
use App\Service\OneC\Actions\AddressAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendUserAdressToOneC implements ShouldQueue
{
    private $action;

    /**
     * SendUserAdressToOneC constructor.
     * @param AddressAction $action
     */
    public function __construct(AddressAction $action)
    {
        $this->action = $action;
    }

    /**
     * @param OnUserAdressAdded $event
     */
    public function handle(OnUserAdressAdded $event)
    {
        try {
            $response =  $this->action->store($event->getAdress()->load(['city', 'users'])->toArray());
            $responseBody = json_decode($response->getBody())[0];
            $event->getAdress()->fill(['guid' => $responseBody->guid])->save();
        } catch (\InvalidArgumentException $e) {
            Log::error(static::class, [
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error(static::class, [
                'message' => $e->getMessage(),
            ]);
        }

        $this->action->connectToUser($event->getAdress()->id, $event->getUser()->id);
    }
}
