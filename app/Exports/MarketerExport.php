<?php

namespace App\Exports;

use App\Marketer;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class MarketerExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $collection = collect(['name', 'family']);
        $collection = $collection->concat(['kiani'])->concat(['rajai']);
        $list = Marketer::join('users', 'users.id', '=', 'marketer.user_id')
            ->select('users.shaba_code')
            ->addSelect(DB::raw('(
                select concat(users.first_name , " " , users.last_name)
            ) as full_name'))
            ->addSelect(DB::raw('(
                select sum(reagent_code.reagented_user_fee)
                from reagent_code
                where reagent_code.reagent_code = users.mobile and 
                reagent_code.checkout = 0 
            ) as total_credit'))
            ->get();
        $list->prepend([
            'کد شبا',
            'نام بازاریاب',
            'مبلغ طلبکاری'
        ]);
        return $list;
    }
}
