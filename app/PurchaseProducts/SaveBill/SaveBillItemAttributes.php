<?php


namespace App\PurchaseProducts\SaveBill;


use App\BillItem;
use App\BillItemAttribute;
use App\CartAttribute;
use App\Discount;

// this is a helper class for saving cart item attribute to invoice
class SaveBillItemAttributes
{
    private $billItem;
    private $cartId;
    private $cartAttributes;
    public function __construct(BillItem $billItem , $cartId ,?Discount $discount)
    {
        $this->billItem = $billItem;
        $this->cartId = $cartId;
        $this->cartAttributes = $this->getCartAttributes();
        $this->discount = $discount ? Discount::getDiscountFor($discount->code , $billItem->product->store->store_type , $billItem->product->id) : null;
    }

    /**
     * get cart attributes
     * @return CartAttribute[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getCartAttributes(){
        return CartAttribute::where('cart_id', $this->cartId)
            ->with(['attribute', 'attribute.attribute'])
            ->get();
    }

    /**
     *  save attributes
     * @return array
     */
    public function save(){
        $billItemAttributes = [];
        foreach ($this->cartAttributes as $attr) {
            $bItemAttr = BillItemAttribute::create([
                'bill_item_id' => $this->billItem->id,
                'product_attribute_id' => $attr->attribute->id,
                'extra_price' => $this->discount ? $this->discount->applyOn($attr->attribute->extra_price) : $attr->attribute->extra_price,
                'type' => $attr->attribute->attribute->type,
                'title' => $attr->attribute->title,
            ]);
            $billItemAttributes[] = $bItemAttr;
        }
        return $billItemAttributes;
    }
}