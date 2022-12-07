<?php


namespace App\PurchaseProducts\SaveBill;


use App\Address;
use App\Bill;
use App\Cart;
use App\PurchaseProducts\SaveBill\Payments\PaymentTypeInterface;
use App\User;
// this class save bill data to database
class SaveBill
{
    private $payType;
    private $userId;
    private $address;
    private $storeId;

    public function __construct($userId, $storeId, $addressId, $paymentType)
    {
        $this->userId = $userId;
        $this->address = Address::join('city', 'city.id', 'address.city_id')
            ->join('province', 'province.id', 'city.province_id')
            ->where('address.id', $addressId)
            ->select('city.id as city_id', 'city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
            ->first();
        $this->payType = $paymentType;
        $this->storeId = $storeId;
    }

    public function getUser()
    {
        return User::find($this->userId);
    }

    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return Bill|\Illuminate\Database\Eloquent\Model
     */
    public function save()
    {

        // here the number of days it takes to arrive is calculated
        // depending on the city of residence of the buyer was tehran or not, delivery time varies
        $field = $this->address->city_id == 118 ? 'product_seller.deliver_time_in_tehran' : 'product_seller.deliver_time_in_other_towns';
        // delivery time is equal to the maximum delivery time in all shopping cart products
        $maxDeliveryDays = Cart::where('user_id', $this->userId)
            ->join('product_seller', 'product_seller.id', '=', 'cart.product_seller_id')
            ->where('cart.store_id', $this->storeId)
            ->max($field);

        // saving bill
        $bill = Bill::create([
            'store_id' => $this->storeId,
            'user_id' => $this->userId,
            'address_id' => $this->address->address_id,
            'address' => ' استان ' . $this->address->province_name . ' شهر ' . $this->address->city_name . ' ' . $this->address->address,
            'customer_lat' => $this->address->latitude,
            'customer_lng' => $this->address->longitude,
            'pay_type' => $this->payType == 'wallet' ? 'wallet' : 'online',
            'delivery_days' => $maxDeliveryDays
        ]);
        return $bill;
    }

}