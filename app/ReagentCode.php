<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReagentCode
 *
 * @property int $id
 * @property int $user_id
 * @property string $reagent_code
 * @property int $reagent_user_fee
 * @property int $reagented_user_fee
 * @property string $type
 * @property int $checkout
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereCheckout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereReagentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereReagentUserFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereReagentedUserFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReagentCode whereUserId($value)
 * @mixin \Eloquent
 */
class ReagentCode extends Model
{
    protected $table = 'reagent_code';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
