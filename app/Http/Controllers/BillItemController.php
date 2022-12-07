<?php

namespace App\Http\Controllers;

use App\Bill;
use App\BillItem;
use App\Libraries\Swal;
use Illuminate\Support\Facades\Session;

class BillItemController extends Controller
{
    public function show(Bill $bill)
    {
        $bill->load('user');
        $billItems = BillItem::select('bill_item.*')
            ->where('bill_item.bill_id', $bill->id)
            ->selectRaw('( (ROUND(bill_item.price - ((bill_item.price * bill_item.discount) / 100)) * bill_item.quantity )) as total_price')
            ->get();
        $billItemsInfo = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
            ->join('store', 'store.id', '=', 'bill.store_id')
            ->join('users', 'users.id', '=', 'bill.user_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->join('product_seller', 'product_seller.id', 'bill_item.product_id')
            ->join('address as buyer_address', 'buyer_address.id', '=', 'bill.address_id')
            ->join('address as seller_address', 'seller_address.id', '=', 'store.address_id')
            ->select('bill_item.*', 'store.name as store_name', 'guild.name as guild_name', 'store.phone_number', 'users.mobile', 'bill.pay_id',
                'buyer_address.address as buyer_address', 'seller_address.address as seller_address', 'bill.status as bill_status', 'bill.pay_type')
            ->selectRaw('concat (users.first_name , " " , users.last_name) as full_name')
            ->selectRaw('(select COUNT(*) FROM bill_item where bill_item.bill_id = bill.id) as billItemCount')
            ->selectRaw('(
            select sum(ROUND((price * quantity) - (((price * quantity) * discount) / 100))) from bill_item
            where bill_item.bill_id = bill.id
            ) as total_price')
            ->where('bill_item.bill_id', $bill->id)
            ->first();
        return view('admin.billItem.index', compact(['bill', 'billItems', 'billItemsInfo']));
    }

    public function userInvoiceBillItem(Bill $bill)
    {
        $bill->store = $bill->store->name;
        $bill->address = $bill->address()->first()->address;
        $billItems = $bill->billItems;
        $billItems->each(function ($billItem) {
            $billItem->totalPrice = $billItem->price + $billItem->commission_price * $billItem->quantity - (($billItem->price * $billItem->quantity * $billItem->discount) / 100);
        });
        
        return view('frontend.my-account.bill-items.purchase', compact('bill', 'billItems'));
    }

    public function userSalesBillItem(Bill $bill)
    {
        $bill->store = $bill->store->name;
//        $bill->address = $bill->address()->first()->address;
        $billItems = $bill->billItems;
        $billItems->each(function ($billItem) {
            $billItem->totalPrice = $billItem->price * $billItem->quantity - (($billItem->price * $billItem->quantity * $billItem->discount) / 100);
        });
        return view('frontend.my-account.bill-items.sales', compact('bill', 'billItems'));
    }

}
