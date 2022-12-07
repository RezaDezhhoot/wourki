<?php

namespace App\Http\Controllers\API;

use App\AccountingDocuments;
use App\Http\Controllers\Controller;
use App\Store;
use Illuminate\Http\Request;

class AccountingDocumentApi extends Controller
{
    public function index(Request $request)
    {
        $store = Store::where('id', $request->id)->first();
        $lists = $totalBalance = null;
        if ($store) {
            $lists = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->whereNotIn('accounting_documents.type', ['upgrade', 'plan', 'ad'])
                ->where(function ($query) use ($store) {
                    $query->when(function ($collection) use ($store) {
                        return isset($store) && !is_null($store);
                    }, function ($query) use ($store) {
                        $query->where('bill.store_id', $store->id)
                            ->orWhere('checkouts.store_id', $store->id)
                            ->orWhere('accounting_documents.market_id', $store->id);
                    });
                })
                ->select('accounting_documents.*', 'bill.pay_id as billPayID', 'checkouts.pay_id as checkoutPayID')
                ->get();
            $sumBills = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->whereIn('accounting_documents.type', ['bill', 'commission'])
            // ->where('bill.status', '=', 'delivered')
            ->where(function ($query) use ($request, $store) {
                $query->when(function ($collection) use ($store) {
                    return isset($store) && !is_null($store);
                }, function ($query) use ($store) {
                    $query->where('bill.store_id', $store->id)
                        ->orWhere('checkouts.store_id', $store->id)
                        ->orWhere('accounting_documents.market_id', $store->id);
                });
            })
                ->select('accounting_documents.balance')
                ->sum('balance');

            $sumCheckouts = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->where('accounting_documents.type', '=', 'checkout')
            ->where(function ($query) use ($request, $store) {
                $query->when(function ($collection) use ($store) {
                    return isset($store) && !is_null($store);
                }, function ($query) use ($store) {
                    $query->where('bill.store_id', $store->id)
                        ->orWhere('checkouts.store_id', $store->id);
                });
                // ->orWhere('checkouts.user_id', $user->id);

            })
                ->select('accounting_documents.balance')
                ->sum('balance');

            $totalBalance = $sumBills - $sumCheckouts;
            return response()->json(['list' => $lists, 'balance' => $totalBalance], 200);
        }else {
            return response()->json(['list' => [] , 'balance' => 0]);
        }

    }
}
