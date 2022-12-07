<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserReferes
 *
 * @property int $id
 * @property int $referrer_user_id
 * @property string $referred_mobile_number
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes whereReferredMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes whereReferrerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserReferes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserReferes extends Model
{
    protected $table = 'user_refers';
}