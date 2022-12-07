<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Category
 *
 * @property int $id
 * @property string $name
 * @property int $guild_id
 * @property string|null $icon
 * @property int $commission
 * @property-read \App\Guild $guild
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SubCategory[] $subCategories
 * @property-read int|null $sub_categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereGuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;

    public function subCategories(){
        return $this->hasMany(SubCategory::class , 'category_id');
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }
}
