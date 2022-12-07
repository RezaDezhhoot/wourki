<?php

namespace App\PurchaseProducts\Wallet;

use App\Libraries\Swal;
use App\Wallet;

class WalletHandler
{
    public function checkOutHandler()
    {

    }

    public function getPositiveWalletRecords($type, $userId)
    {
        $allPositiveInventory = [];
        $authId = auth()->id();
        if ($userId) {
            $authId = $userId;
        }

        //find positive row that has available amount
        $walletRecords = Wallet::where('wallet_type', '!=', 'output')->where('wallet_type', '!=', 'buy_ad')->where('wallet_type', '!=', 'buy_plan');

        if ($type == 'checkout') {
            $walletRecords = $walletRecords->where('payable', 1);
        }

        $walletRecords = $walletRecords->where('user_id', $authId)->orderby('created_at', 'asc')->with('reducedFrom')->orderby('payable', 'asc')->get();

        return $walletRecords;
    }

    public function getAvailablePositiveWalletRecords($allPositiveRecords)
    {
        $allAvailablePositiveRecords = [];
        foreach ($allPositiveRecords as $rowKey => $positiveRow) {
            $sumAmount = 0;
//            dd($positiveRow);
            foreach ($positiveRow->reducedFrom as $invRow) {
                $sumAmount += $invRow->pivot->Amount;
            }
//            dd($sumAmount);

            if ($positiveRow['cost'] > $sumAmount) {
                $positiveRow['Available'] = $positiveRow['cost'] - $sumAmount;
                $allAvailablePositiveRecords[] = $positiveRow;
            }
        }

        return $allAvailablePositiveRecords;
    }

    public function NegativeRecordReducer($negativeRow, $type = '', $userId = '')
    {

        $allPositiveRecords = $this->getPositiveWalletRecords($type, $userId);

        $allAvailablePositiveRecords = $this->getAvailablePositiveWalletRecords($allPositiveRecords);
        $allAvailablePositiveRecordsKeys = array_keys($allAvailablePositiveRecords);
        $remainedNegativeAmount = abs($negativeRow['cost']);
        $reducedRows = [];
        $notEnoughAvailableInventory = false;

        if (empty($allAvailablePositiveRecords)) {
        } else {
            foreach ($allAvailablePositiveRecords as $key => $positiveRow) {
                if ($remainedNegativeAmount !== 0) {
                    if ($positiveRow['Available'] < $remainedNegativeAmount) {
                        $reducedRows[$positiveRow['id']] = ['Amount' => $positiveRow['Available']];
                        $remainedNegativeAmount = $remainedNegativeAmount - $positiveRow['Available'];
                    } else {
                        $reducedRows[$positiveRow['id']] = ['Amount' => $remainedNegativeAmount];
                        $remainedNegativeAmount = 0;
                        break;
                    }
                }
                //not enough wallet
                if ($key === end($allAvailablePositiveRecordsKeys) and $remainedNegativeAmount !== 0) {
                    $reducedRows = false;
                }
            }
        }
        if ($reducedRows === false) {
            Swal::success('اخطار!', 'موجودی کیف پول ناکافی است');
        }
        return $reducedRows;
    }

    public function getSumAvailableWalletRecords($type = '', $userId = '')
    {
        $allPositiveRecords = $this->getPositiveWalletRecords($type, $userId);

        $records = $this->getAvailablePositiveWalletRecords($allPositiveRecords);
        return collect($records)->sum('Available');
    }
}