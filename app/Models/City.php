<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 *
 * @property integer $id
 * @property string  $city
 * @property string  $district
 * @property integer index
 * @package App\Models
 */
class City extends Model
{
    protected $fillable = [
        'city',
        'district',
        'index',
    ];

    protected $table = 'cities';

    public $timestamps = false;

    public function streets()
    {
        return $this->hasMany(Street::class, 'city_id', 'id');
    }
}
