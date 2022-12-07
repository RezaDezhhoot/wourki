<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use App\User;
use Log;

class CheckoutRequestsApi extends Controller
{
   public function store(){
        $authUserId = auth()->guard('api')->user()->id;

        $user = User::with(['wallet' => function ($q) {
            $q->where('payable', 1);
        }, 'checkoutRequest' => function ($q) {
            $q->where('status', 0);
        }])->where('id', $authUserId)->first();

        $totalPositive = $user->wallet->sum('cost');
        $totalReduced = 0;

        foreach ($user->wallet as $wallet) {
            $wallet->load('reducedFrom');
            if (!is_null($wallet->reducedFrom)) {
                foreach ($wallet->reducedFrom as $reducedFrom) {
                    $totalReduced += $reducedFrom->pivot->Amount;
                }
            }
        };

        if (is_null($user->wallet)) {
            return response()->json(['status' => 400 , 'message' => 'مبلغ قابل برداشت در کیف پول شما وجود ندارد' ] , 200);
        }


        if ($totalPositive <= $totalReduced) {
            return response()->json(['status' => 400, 'message' => 'مبلغ قابل برداشت در کیف پول شما وجود ندارد'], 200);
        }

        if (count($user->checkoutRequest)) {
            return response()->json(['status' => 400, 'message' => 'درخواست تعیین تکلیف نشده ای از شما وجود دارد'], 200);
        }

        try {
            $commit = \DB::transaction(function () use ($user) {
                $user->checkoutRequest()->create([
                    'approval' => 0,
                    'checkout_id' => 0
                ]);

                return true;
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 400, 'message' => 'خطایی در سرور رخ داده است'], 200);
            Log::info('error happened :');
            Log::info($e->getMessage());
            
        }
        if ($commit == true) {
            return response()->json(['status' => 200 , 'message' => 'درخواست شما با موفقیت ذخیره شد'] , 200);
        }
        return response()->json(['status' => 400, 'message' => 'خطایی در سرور رخ داده است'], 200);

   }
}
