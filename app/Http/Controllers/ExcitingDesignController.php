<?php

namespace App\Http\Controllers;

use App\Exciting_design;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class ExcitingDesignController extends Controller
{
    public function index()
    {
        $excitingDesign = Exciting_design::all();
        return view('admin.excitingDesign' , compact('excitingDesign'));
    }

    public function store(Request $request)
    {
        for ($i = 0 ; $i < count($request->from_date) ; $i++) {
            Exciting_design::updateOrCreate([
                'from_date' => \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('d/m/Y', $request->from_date[$i]),
                'to_date' => \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('d/m/Y', $request->to_date[$i]),
                'price' => $request->price[$i],
                'gift' => $request->gift[$i],
            ]);
        }
        return back();
    }

    public function delete(Exciting_design $exciting_design)
    {
        $exciting_design->delete();
        return back();
    }
}
