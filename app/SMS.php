<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class SMS extends Model
{
    protected $table = 'sms_logs';

    protected $guarded = [];
}
