<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Province
 *
 * @property int $id
 * @property string $name
 * @property int $deleted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\City[] $city
 * @property-read int|null $city_count
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 * @mixin \Eloquent
 */
class Province extends Model
{
    protected $table = 'province';
    public $timestamps = false;
    public function city(){
        return $this->hasMany('App\City');
    }
}
