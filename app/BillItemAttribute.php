<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BillItemAttribute
 *
 * @property int $id
 * @property int $bill_item_id
 * @property int $product_attribute_id
 * @property int $extra_price
 * @property string $type
 * @property string $title
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereBillItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereExtraPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereProductAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItemAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BillItemAttribute extends Model
{
    protected $table = 'bill_item_attribute';
    protected $guarded = [];
}
