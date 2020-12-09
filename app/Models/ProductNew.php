<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class ProductNew extends Model
{
    const STATUS_SPECIAL = 'special';

    protected $table = 'products_new';

    protected $fillable = [
        'id',
        'guid',
        'preview_picture',
        'detail_picture',
        'name',
        'description',
        'price',
        'old_price',
        'category_id',
        'position',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'cid');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'related_products', 'product_id', 'related_product_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function isMain(): bool
    {
        return $this->id === 1;
    }

    public function price(int $qty): float
    {
        if ($this->id === 1 && $qty === 1) {
            return $this->price + 10;
        }

        return $this->price;
    }

    public function getSmallPhotoAttribute(): string
    {
        return '/storage/productsnew/'.$this->guid.'-small.jpg';
    }

    public function getDetailsPhotoAttribute(): string
    {
        return '/storage/productsnew/'.$this->guid.'-details.jpg';
    }

    public function getDiscountAttribute():? float
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return $this->price / $this->old_price * 100 - 100;
        }

        return null;
    }
}
