<?php


namespace App\PurchaseProducts\SaveBill;

use App\Address;
use App\Bill;
use App\BillItem;
use App\Cart;
use App\Discount;
use App\MarketCommission;
use App\ProductSeller;
use Morilog\Jalali\Jalalian;

// this is an helper class for saving cart products to invoice
class SaveBillItem
{
    private $bill;
    private $cartItems;
    private $address;
    private $discount;
    public function __construct(Bill $bill , ?Discount $discount)
    {
        $this->discount = $discount;
        $this->bill = $bill;
        // get the shopping car products that are associated with the store he want to buy from
        $this->cartItems = $this->getCartItems();
        $this->address = Address::join('city', 'city.id', 'address.city_id')
            ->join('province', 'province.id', 'city.province_id')
            ->where('address.id', $bill->address_id)
            ->select('city.id as city_id', 'city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
            ->first();
    }

    /**
     * reduce product inventory after successful purchase
     * @param $productId
     * @param $quantity
     */
    private function decreaseProductInventory($productId , $quantity){
        $product = ProductSeller::find($productId);
        $product->quantity -= $quantity;
        $product->save();
    }

    private function getCartItems(){
        return Cart::where('user_id', $this->bill->user_id)
            ->where('store_id', $this->bill->store_id)
            ->get();
    }

    public function save(){
        $billItems = [];
        foreach($this->cartItems as $cartItem){
            $product = ProductSeller::find($cartItem->product_seller_id);
            $this->decreaseProductInventory($product->id , $cartItem->quantity);
            $discount = $this->discount ? Discount::getDiscountFor($this->discount->code , $product->store->store_type , $product->id) : null;
            $price = $discount ? $discount->applyOn($product->price) : $product->price;
            $commisionPrice = 0;
            // checking for market
            if($cartItem->market_id){
                $commision = MarketCommission::where('category_id' , $product->category_id)->first();
                if($commision){
                $commisionPrice = $commision->applyOn($price);
                $price -= $commisionPrice;
                }
            }
            // calculate shipping price
            $shippingPrice = $this->address->city_id == 118 ? $product->shipping_price_to_tehran : $product->shipping_price_to_other_towns;
            if ($this->discount) {
                if ($this->discount->discountable_type == "all-sending") {
                    $shippingPrice = $this->discount->applyOn($shippingPrice);
                }
                if ($this->discount->discountable_type == "store-sending" && $product->store_id == $this->discount->discountable_id) {
                    $shippingPrice = $this->discount->applyOn($shippingPrice);
                }
                if ($this->discount->discountable_type == "product-sending" && $product->id == $this->discount->discountable_id) {
                    $shippingPrice = $this->discount->applyOn($shippingPrice);
                }
            }
            // save bill item
            $bItem = BillItem::create([
                'bill_id' => $this->bill->id,
                'product_id' => $cartItem->product_seller_id,
                'product_name' => $product->name,
                'price' => $price,
                'discount' => $product->discount ,
                'quantity' => $cartItem->quantity,
                'shipping_price' => $shippingPrice,
                'market_id' => $cartItem->market_id,
                'commission_price' => $commisionPrice
            ]);
            $billItems[] = $bItem;
        }
        return $billItems;

    }
}