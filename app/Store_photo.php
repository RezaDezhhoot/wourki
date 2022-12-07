<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Store_photo
 *
 * @property int $id
 * @property int $store_id
 * @property string $photo_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo wherePhotoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store_photo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store_photo extends Model
{
    protected $table = 'store_photo';

    public function store()
    {
        return $this->belongsTo(Store::class , 'store_id');
    }

    
}
