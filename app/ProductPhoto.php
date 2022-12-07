<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductPhoto
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property string|null $alt
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Products $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPhoto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductPhoto extends Model
{
    protected $guarded = ['id'];
    protected $table = 'product_pic';

    public function product(){
        return $this->belongsTo(Products::class , 'product_id');
    }
}
