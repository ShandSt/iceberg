<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    const STATUS_SPECIAL = 'special';

    protected $fillable = [
        'id',
        'guid',
        'preview_picture',
        'detail_picture',
        'name',
        'description',
        'price',
        'category',
        'status',
    ];

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(static::class , 'related_products','product_id','related_product_id');

    }
}
