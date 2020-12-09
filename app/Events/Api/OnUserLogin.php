<?php

namespace App\Events\Api;

use App\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OnUserLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var User
     */
    private $user;


    /**
     * @var Carbon
     */
    private $timestamp;

    /**
     * OnUserLogin constructor.
     * @param User $user
     * @param Carbon $timestamp
     */
    public function __construct(User $user, Carbon $timestamp)
    {
        $this->user = $user;
        $this->timestamp = $timestamp;
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

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Carbon
     */
    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }
}
