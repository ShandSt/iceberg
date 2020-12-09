<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

/**
 * Class Tag
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property int $position
 */
class Tag extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'guid',
        'name',
        'position',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(
            'orderByPosition',
            function (Builder $query) {
                return $query->orderBy('position');
            }
        );
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductNew::class, 'product_tag', 'tag_id', 'product_id');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotEmpty(Builder $query): Builder
    {
        return $query->has('products');
    }

    /**
     * @return bool
     */
    public function hasImage(): bool
    {
        return Storage::exists('/public/tags/'.$this->guid.'.svg');
    }

    public function image(): string
    {
        return '/storage/tags/'.$this->guid.'.svg';
    }
}
