<?php

namespace App;

use App\Process\PlanSubscriptions;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Plan
 *
 * @property int $id
 * @property string $plan_name
 * @property int $month_inrterval
 * @property int $price
 * @property string $description
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMonthInrterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePlanName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Plan extends Model
{
    protected $table = 'seller_plans';

    public function planSubscriptions()
    {
        return $this->hasMany(PlanSubscriptions::class , 'plan_id');
    }
}
