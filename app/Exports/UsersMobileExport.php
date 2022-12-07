<?php

namespace App\Exports;

use App\Setting;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersMobileExport implements FromCollection
{
    private $rowsOffset;
    public function __construct($rowsOffset)
    {
        $this->rowsOffset = $rowsOffset;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $defaultRowsNum = Setting::first()->excel_export_rows_num;
        $list = User::where('banned' , 0)
            ->select('mobile' , DB::raw('(concat(users.first_name, " ", users.last_name)) as full_name'))
            ->offset($this->rowsOffset)
            ->limit($defaultRowsNum)
            ->get();
        $list->prepend([
            'شماره موبایل',
            'نام',
        ]);
        return $list;
    }
}
