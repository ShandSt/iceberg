<?php

namespace App\Events\Api;

use App\Models\Address;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OnUserAdressAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $adress;

    private $user;

    /**
     * OnUserAdressAdded constructor.
     * @param Address $adress
     */
    public function __construct(Address $adress, User $user)
    {
        $this->adress = $adress;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getAdress(): Address
    {
        return $this->adress;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
