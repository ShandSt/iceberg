<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Street
 *
 * @property integer $id
 * @property integer  $city_id
 * @property string  $street
 * @property integer index
 * @package App\Models
 */
class Street extends Model
{
    protected $fillable = [
        'city_id',
        'street',
        'index',
    ];

    protected $table = 'streets';

    public $timestamps = false;
}
