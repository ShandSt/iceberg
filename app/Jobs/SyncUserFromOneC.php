<?php

namespace App\Jobs;

use App\Events\Api\OnUserBalanceChanged;
use App\Service\OneC\Actions\UserAction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncUserFromOneC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * SyncUserFromOneC constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param UserAction $service
     */
    public function handle(UserAction $service)
    {
        if ($this->user->guid !== null) {
            $user = $service->getUser($this->user->guid);
            $user = json_decode($user->getBody()->getContents(), true);


            if ($user['balance'] != $this->user->balance) {
                $this->user->balance = $user['balance'];
                event(new OnUserBalanceChanged($this->user));
            }

            $bottles = $this->getBottles($service);


            $this->user->bottles = $bottles['count'];
            $this->user->consumption->consumption = floatval($bottles['guess']);
            $this->user->consumption->save();


            $this->user->save();
        } else {
            $service->addUser($this->user->toArray());
        }
    }

    private function getBottles(UserAction $action): array
    {
        $content = $action->getBottles($this->user->guid);

        return json_decode($content->getBody()->getContents(), true);
    }
}
