<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AdsPosition
 *
 * @property int $id
 * @property string $ads_position
 * @property string $name
 * @property string $price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ads[] $ads
 * @property-read int|null $ads_count
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition whereAdsPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsPosition wherePrice($value)
 * @mixin \Eloquent
 */
class AdsPosition extends Model
{
    protected $table = 'ads_position';
    public $timestamps = false;

    public function ads(){
        return $this->hasMany(Ads::class , 'ads_position_id');
    }
}
