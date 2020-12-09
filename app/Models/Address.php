<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    protected $fillable = [
        'guid',
        'street',
        'house',
        'entrance',
        'floor',
        'apartment',
        'comment',
        'city_id',
        'consumption',
    ];


    protected $table = 'address';

    protected $with = ['city'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_address', 'address_id', 'user_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function getName()
    {
        return sprintf('%s %s %s %s', _('street'), $this->street, _('building'), $this->house);
    }

    public function getTitleAttribute(): string
    {
        $address = sprintf('%s, %s, д.%s', $this->city->city ?? '', $this->street, $this->house);
        if ($this->apartment) {
            $address .= ', кв.'.$this->apartment;
        }

        return $address;
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
