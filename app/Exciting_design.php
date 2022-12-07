<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Exciting_design
 *
 * @property int $id
 * @property string $from_date
 * @property string $to_date
 * @property int $price
 * @property int $gift
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design whereGift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exciting_design whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Exciting_design extends Model
{
    protected $table = 'exciting_design';
    protected $guarded = [];
}
