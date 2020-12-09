<?php

namespace App\Observers;

use App\Models\Address;
use App\Service\OneC\Actions\AddressAction;
use Illuminate\Support\Facades\Log;

class AddressObserver
{
    public function created(Address $address)
    {
        /**
         * @var $service AddressAction
         */
//        $service = app()->make(AddressAction::class);

//        $this->updateGuid($service, $address);
    }

    private function updateGuid(AddressAction $service, Address $address)
    {
        try {
            $response = $service->store($address->toArray());
        } catch (\InvalidArgumentException $e) {
            Log::error(static::class, [
                'message' => $e->getMessage()
            ]);

            $address->delete();
        } catch (\Exception $e) {
            Log::error(static::class, [
                'message' => $e->getMessage(),
            ]);

            $address->delete();
        }

        $body = $response->getBody()->getContents();
        $code = $response->getStatusCode();


        $json = json_decode($body, true);

        $address->guid = $json['guid'];
        $address->save();


    }
}
