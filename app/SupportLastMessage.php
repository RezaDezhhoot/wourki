<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SupportLastMessage
 *
 * @property int $id
 * @property int $user_id
 * @property string $last_message
 * @property string $last_message_datetime
 * @property int $view
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereLastMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereLastMessageDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportLastMessage whereView($value)
 * @mixin \Eloquent
 */
class SupportLastMessage extends Model
{
    protected $table = 'support_last_message';

    protected $fillable = [
        'user_id',
        'last_message',
        'last_message_datetime',
        'view'
    ];
}
