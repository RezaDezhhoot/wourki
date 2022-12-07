<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BillItem
 *
 * @property int $id
 * @property int $bill_id
 * @property int $product_id
 * @property string $product_name
 * @property int $price
 * @property int $discount
 * @property int $quantity
 * @property string $shipping_price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BillItemAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Bill $bill
 * @property-read \App\ProductSeller $product
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereShippingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BillItem extends Model
{
    protected $table = 'bill_item';
    protected $guarded = [];

    public function getBillItemPriceWithCommission($billId)
    {
        $price = 0;
        $billItems = BillItem::where('bill_id' , $billId)->get();
        foreach ($billItems as $billItem){
            $billAttrExists = BillItemAttribute::where('bill_item_id' , $billItem->id)->exists();
            if ($billAttrExists) {
                $sumBillAttrPrice = BillItemAttribute::where('bill_item_id' , $billItem->id)->sum('extra_price');
                $price += ($billItem->price - (($billItem->price * $billItem->discount) / 100) + $sumBillAttrPrice ) * $billItem->quantity + $billItem->shipping_price;
            } else {
                $price += ( $billItem->price - (($billItem->price * $billItem->discount) / 100)) * $billItem->quantity + $billItem->shipping_price;
            }
        }
        return $price;
    }
    public function getBillItemPrice($billId)
    {
        $price = 0;
        $billItems = BillItem::where('bill_id', $billId)->get();
        foreach ($billItems as $billItem) {
            $billAttrExists = BillItemAttribute::where('bill_item_id', $billItem->id)->exists();
            if ($billAttrExists) {
                $sumBillAttrPrice = BillItemAttribute::where('bill_item_id', $billItem->id)->sum('extra_price');
                $price += ($billItem->price + $billItem->commission_price - (($billItem->price * $billItem->discount) / 100) + $sumBillAttrPrice) * $billItem->quantity + $billItem->shipping_price;
            } else {
                $price += ($billItem->price + $billItem->commission_price - (($billItem->price * $billItem->discount) / 100)) * $billItem->quantity + $billItem->shipping_price;
            }
        }
        return $price;
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductSeller::class , 'product_id');
    }

    public function attributes()
    {
        return $this->hasMany(BillItemAttribute::class , 'bill_item_id');
    }

}
