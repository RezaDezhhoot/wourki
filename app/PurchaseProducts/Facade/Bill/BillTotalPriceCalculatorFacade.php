<?php


namespace App\PurchaseProducts\Facade\Bill;


use App\Bill;

class BillTotalPriceCalculatorFacade
{
    private $bill;
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    public function getTotalPrice(){
        $billItems = $this->bill->billItems()->with(['attributes'])->get();
        $totalPrice = 0;
        $billItemsPrice = 0;
        foreach($billItems as $billItem){

            // calculate attributes price and store it to a variable
            $attrPrice = 0;
            $attributes = $billItem->attributes;
            foreach($attributes as $attr){
                $attrPrice += $attr->extra_price;
            }

            // calculate the net price of bill items
            $billItemsPrice += $billItem->quantity * (
                    $billItem->price - (
                        $billItem->discount / 100 * $billItem->price
                    )

                ) + $billItem->shipping_price;
            $totalPrice += $billItemsPrice + $attrPrice;
        }
        return $totalPrice;
    }

}