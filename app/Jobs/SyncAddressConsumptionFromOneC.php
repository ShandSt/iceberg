<?php

namespace App\Jobs;

use App\Service\OneC\Actions\AddressAction;
use App\Models\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncAddressConsumptionFromOneC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $address;

    /**
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * @param AddressAction $service
     */
    public function handle(AddressAction $service)
    {
        $address = $service->show($this->address->id);
        $address = json_decode($address->getBody()->getContents(), true);

        $this->address->consumption = $address[0]['consumption'];
        $this->address->save();
    }
}
