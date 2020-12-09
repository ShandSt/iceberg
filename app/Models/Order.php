<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{

    const STATUS_NEW = 'Checking';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_CONFIRMED = 'Accepted';
    const STATUS_IN_TRANSIT = 'Transit';
    const STATUS_COMPLETED = 'Fulfilled';
    const STATUS_CANCELED = 'Canceled';

    const LAST_SAVED_USER = 'USER';
    const LAST_SAVED_SYSTEM = 'SYSTEM';

    const STATUS_PAYED = 'Paid';

    const ONE_BOTTLE_LITRS = 18.9;

    protected $fillable = [
        'guid',
        'user_id',
        'address_id',
        'date_of_delivery',
        'date_of_delivery_variants',
        'payment_method',
        'payment_status',
        'payment_hash',
        'price',
        'bottles',
        'status',
        'order_source',
        'delivery_sms',
        'back_call',
        'intercom_does_not_work',
        'contactless',
        'comment',
        'popup_message',
    ];

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

    protected $casts = [
        'date_of_delivery_variants' => 'array',
        'date_of_delivery' => 'array',
        'delivery_sms' => 'boolean',
        'back_call' => 'boolean',
        'intercom_does_not_work' => 'boolean',
        'contactless' => 'boolean',
        'sync_failed_at' => 'datetime',
        'sync_attempts_count' => 'integer',
    ];

    protected $appends = [
        'date_of_delivery_from_server',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductNew::class, 'orders_products', 'order_id', 'product_id')->withPivot([
            'product_count',
        ]);
    }

    public function setLitrsAttribute($value)
    {
        $this->attributes['litrs'] = round($value, 1);
    }

    public function getDateOfDeliveryFromServerAttribute(): bool
    {
        if (is_array($this->date_of_delivery_variants) && count($this->date_of_delivery_variants) === 1) {
            return true;
        }

        return false;
    }
}
