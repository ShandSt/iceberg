<?php

namespace App\Events\Api;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OnConsumptionUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;

    private $consumption;

    /**
     * OnConsumptionUpdate constructor.
     * @param User $user
     * @param float $consumption
     */
    public function __construct(User $user, float $consumption)
    {
        $this->user = $user;
        $this->consumption = $consumption;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function getConsumption(): float
    {
        return $this->consumption;
    }
}
