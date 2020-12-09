<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->settings()->create([
            'data' => []
        ]);

        $user->consumption()->create([
            'consumption' => 0
        ]);
    }
}