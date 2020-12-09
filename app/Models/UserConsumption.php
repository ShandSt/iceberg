<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConsumption extends Model
{
    protected $fillable  = [
        'user_id',
        'consumption',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    const LAST_SAVED_SYSTEM = 'system';
    const LAST_SAVED_USER = 'user';
}
