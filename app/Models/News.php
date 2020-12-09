<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Events\Api\OnNewsCreated;

class News extends Model
{
    protected $fillable = [
        'title',
        'text',
        'product_id',
        'type',
        'image',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

//    public static function boot()
//    {
//        parent::boot();
//
//        static::created(function($model) {
//            event(new OnNewsCreated($model));
//        });
//    }
}
