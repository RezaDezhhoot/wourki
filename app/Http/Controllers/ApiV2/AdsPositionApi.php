<?php

namespace App\Http\Controllers\ApiV2;

use App\Ads;
use App\AdsPosition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdsPositionApi extends Controller
{
    public function index(){
        $positions = AdsPosition::all();
        return response()->json([
            'status' => 200,
            'message' => 'ads_position_returned',
            'entire' => [
                'list' => $positions
            ]
        ]);
    }
}
