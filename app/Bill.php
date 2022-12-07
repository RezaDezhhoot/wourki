<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Bill
 *
 * @property mixed created_at
 * @property mixed billItems
 * @method static whereIn(string $string, array|string $input)
 * @property int $id
 * @property int $store_id
 * @property int $user_id
 * @property int $address_id
 * @property \App\Address $address
 * @property float|null $customer_lat
 * @property float|null $customer_lng
 * @property string $pay_type
 * @property string|null $pay_id
 * @property string $status
 * @property string|null $reject_reason
 * @property int $delivery_days
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read int|null $bill_items_count
 * @property-read mixed $bill_price
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCustomerLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCustomerLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereDeliveryDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUserId($value)
 * @mixin \Eloquent
 */
class Bill extends Model
{
    protected $table = 'bill';
    protected $guarded = [];
    protected $appends = [
        'bill_price'
    ];
    public function billItems()
    {
        return $this->hasMany(BillItem::class, 'bill_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function calcUserBillPrice()
    {
        $bills = auth()->guard('web')->user()->bills;
        $total = 0;
        foreach ($bills as $bill) {
            $userBillItem = BillItem::where('bill_id', $bill->id)->first();
            $userBillAttrs = BillItemAttribute::where('bill_item_id', $userBillItem->id)->get();
            $attrPrice = 0;
            if ($userBillAttrs) {
                foreach ($userBillAttrs as $billAttr) {
                    $attrPrice += $billAttr->extra_price;
                }
            }
            $price = $this->calcProductDiscount($userBillItem->product->id) * $userBillItem->quantity + $attrPrice;
            $total = $price + $total;
        }

        return $total;
    }

    public function calcProductDiscount($product)
    {
        $product = ProductSeller::find($product);
        $price = $product->price - (($product->price * $product->discount) / 100);
        return $price;
    }

    public function getBillPriceAttribute()
    {
        $billPrice = 0;
        $items = $this->billItems;
        if(count($items))
        $billPrice += $items[0]->getBillItemPriceWithCommission($this->id);
        return $billPrice;
    }
}
