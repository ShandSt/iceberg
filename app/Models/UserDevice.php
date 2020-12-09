<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserDevice extends Model
{
    protected $fillable = [
        'os',
        'token',
        'user_id',
        'device_id',
        'allow_push',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
