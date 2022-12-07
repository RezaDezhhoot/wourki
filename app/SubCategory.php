<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SubCategory
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property int $commission
 * @property string|null $icon
 * @property-read \App\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategory whereName($value)
 * @mixin \Eloquent
 */
class SubCategory extends Model
{
    protected $table = 'sub_category';
    public $timestamps = false;

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
