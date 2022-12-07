<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\UsedDiscount
 *
 * @property int $id
 * @property string $plan_name
 * @property int $user_id
 * @property int $discount_id
 * @property int $price
 * @property int $price_with_discount
 * @property string $pay_type
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent
 */
class UsedDiscount extends Model
{
    use HasFactory;
    protected $table = 'used_discounts';

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function discount(){
        return $this->belongsTo(Discount::class);
    }
}
