<?php

namespace App\Exports;

use App\Store;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class CreditStoresListExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $list = User::select('users.id as user_id', 'users.first_name', 'users.last_name',
            'store.name', 'store.shaba_code')
            ->addSelect(\DB::raw('(
            (
                IFNULL(
                (
                    select sum(bill_item.quantity * (bill_item.price - (bill_item.price * bill_item.discount / 100) ) )
                    from bill_item
                    where bill_item.bill_id = bill.id
                ) , 0 )
                 + 
                IFNULL(
                (
                    select sum(extra_price)
                    from bill_item_attribute
                    join bill_item as bi3 on bi3.id = bill_item_attribute.bill_item_id
                    where bi3.bill_id = bill.id
                ) , 0)
            )
            -
            IFNULL(
            (
                select sum(price)
                from checkouts
                where store_id = bill.store_id
            ) , 0)
            ) as talab'))
            ->join('store', 'store.user_id', '=', 'users.id')
            ->join('bill', 'bill.store_id', '=', 'store.id')
            ->join('bill_item', 'bill_item.bill_id', '=', 'bill.id')
            ->join('accounting_documents as acc_dc', 'acc_dc.bill_id', '=', 'bill.id')
            ->where('acc_dc.type', '=', 'bill')
            ->where('bill.status', '=', 'delivered')
            ->where('bill.pay_type', '=', 'online')
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'store.name', 'store.shaba_code', 'talab')
            ->get();
        foreach ($list as $i => $row) {
            if ($row->talab === 0) {
                $list[$i]->talab = "0";
            }
        }
        $list->prepend([
            'شناسه کاربر',
            'نام ',
            'نام خانوادگی',
            'نام فروشگاه',
            'شماره شبا',
            'مبلغ طلبکاری(تومان)',
        ]);
        return $list;
    }
}
