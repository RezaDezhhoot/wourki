<?php

namespace Tests\Unit;

use App\Address;
use App\PurchaseProducts\Facade\Api\SaveBillFacade;
use App\PurchaseProducts\Strategy\Shipping\Shipping;
use App\PurchaseProducts\Strategy\Shipping\Tehran;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillTest extends TestCase
{
    use DatabaseTransactions;


    public function test_bill_facade_banned_user_in_tehran_should_not_buy_product(){


        $user = factory(User::class)->create();
        $this->actingAs($user);
        $address = factory(Address::class)->create();
        $shippingPlace = new Tehran();
        $shipping = new Shipping($shippingPlace , $user->id);
//        $saveBillFacade = new SaveBillFacade($user->id, $address->id,)
    }
}
