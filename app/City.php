<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\City
 *
 * @property int $id
 * @property int $province_id
 * @property string $name
 * @property int $deleted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read \App\Province $province
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereProvinceId($value)
 * @mixin \Eloquent
 */
class City extends Model
{
    protected $table = 'city';
    protected $guarded = [];
    public $timestamps = false;

    public function province(){
        return $this->belongsTo('App\Province');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class , 'city_id');
    }
}
