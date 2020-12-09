<?php

namespace App;

use App\Models\Address;
use App\Models\ConfirmCode;
use App\Models\Order;
use App\Models\UserAdress;
use App\Models\UserConsumption;
use App\Models\UserDevice;
use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @property UserSetting settings
 */
class User extends Authenticatable
{
    use Notifiable;


    const STATUS_ACTIVE = 'active';
    const STATUS_NOT_ACTIVE = 'not active';
    const STATUS_DISABLE = 'disable';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'type',
        'company_name',
        'inn',
        'guid',
        'has_debt',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token',
    ];

    protected $appends = [
        'send_water_notification',
        'send_news_notification',
        'push_messages_is_allowed',
        'water_notification_days',
    ];


    public function setHasDebtAttribute($value)
    {
        $this->attributes['has_debt'] = $value === true ? 1 : 0;
    }

    public function getHasDebtAttribute()
    {
        return (in_array('has_debt', $this->attributes) && $this->attributes['has_debt'] === 1) ? true : false;
    }


    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    public function code(): HasMany
    {
        return $this->hasMany(ConfirmCode::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function consumption(): HasOne
    {
        return $this->hasOne(UserConsumption::class);
    }

    public function adress(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'users_address', 'user_id', 'address_id');
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'users_address', 'user_id', 'address_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function confirmCodes(): HasMany
    {
        return $this->hasMany(ConfirmCode::class);
    }

    public function getNameAttribute():? string
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }

    public function getSendWaterNotificationAttribute(): ?bool
    {
        return $this->getSettingsValue('sendWaterNotification');
    }

    public function getSendNewsNotificationAttribute(): ?bool
    {
        return $this->getSettingsValue('sendNewsNotification');
    }

    public function getPushMessagesIsAllowedAttribute(): ?bool
    {
        return $this->getSettingsValue('pushMessagesIsAllowed');
    }

    public function getWaterNotificationDaysAttribute(): ?int
    {
        return $this->getSettingsValue('waterNotificationPeriodBefore');
    }

    private function getSettingsValue(string $key)
    {
        return $this->settings->data[$key] ?? null;
    }
}
