<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Service\Push\Contracts\PushMessageContract;
use App\Service\Push\Contracts\PushServiceContract;
use Illuminate\Support\Facades\Log;

class SendPushToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User user
     */
    private $user;

    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, PushMessageContract $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PushServiceContract $service)
    {
        $devices = $this->user->devices;
        if (count($devices) === 0) return;

        foreach ($devices as $device) {
                $this->message->setOs($device->os);
                $service->send($this->message, $device->token);
        }
    }
}
