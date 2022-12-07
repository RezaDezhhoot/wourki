<?php

namespace App\Http\Middleware;

use Closure;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Input;

class convertFarsiNumbers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $farsiNumbers = ['۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۰'];
        $ennumbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
        $requestContent = $request->all();
        foreach ($requestContent as $index => $row) {
            if(!is_array($row)){
                $initString = (string)$row;
                foreach($farsiNumbers as $faIndex => $faValue){
                    $initString = str_replace($faValue , $ennumbers[$faIndex] , $initString);
                }
                if (intval($initString) == $initString  ) {
                    $requestContent[$index] = $initString;
//                Input::replace([$index => $initString]);
                }else if(doubleval($initString) == $initString){
                    $requestContent[$index] = $initString;
//                Input::replace([$index => $initString]);
                }else if(floatval($initString) == $initString){
                    $requestContent[$index] = $initString;
//                Input::replace([$index => $initString]);
                }
                Input::replace($requestContent);
            }

        }
        return $next($request);
    }
}
