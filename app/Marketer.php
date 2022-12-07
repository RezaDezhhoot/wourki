<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Marketer
 *
 * @property int $id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Marketer whereUserId($value)
 * @mixin \Eloquent
 */
class Marketer extends Model
{
    protected $table = 'marketer';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
