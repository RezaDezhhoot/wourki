<?php

namespace App\Exports;

use App\Setting;
use App\Store;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class StoresMobileExport implements FromCollection
{
    private $firstRowIndex;
    public function __construct($firstRowIndex)
    {
        $this->firstRowIndex = $firstRowIndex;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $defaultRowsNum = Setting::first()->excel_export_rows_num;
        $list = Store::whereStatus('approved')
            ->whereVisible(1)
            ->select([
                'id', 'name',
                DB::raw('(
                    select concat(first_name," ",last_name)
                    from users
                    where users.id = store.user_id
                ) as full_name'),
                DB::raw('(
                    select mobile
                    from users
                    where users.id = store.user_id
                ) as mobile'),
            ])
            ->offset($this->firstRowIndex)
            ->limit($defaultRowsNum)
            ->get();
        $list->prepend([
            'ردیف',
            'نام فروشگاه',
            'نام کاربر',
            'شماره موبایل',
        ]);
        return $list;
    }
}
