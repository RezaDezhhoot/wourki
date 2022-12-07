<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Guild
 *
 * @property int $id
 * @property string $name
 * @property string|null $pic
 * @property string $guild_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $categories
 * @property-read int|null $categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|Guild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guild query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guild whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guild wherePic($value)
 * @mixin \Eloquent
 */
class Guild extends Model
{
    protected $table = 'guild';
    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Category::class , 'guild_id');
    }
}
