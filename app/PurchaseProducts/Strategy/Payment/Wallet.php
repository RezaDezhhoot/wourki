<?php


namespace App\PurchaseProducts\Strategy\Payment;


use App\PurchaseProducts\Exceptions\UserNotPassedException;
use App\PurchaseProducts\Exceptions\WalletRestriction;
use App\PurchaseProducts\Strategy\Payment\Exceptions\InvalidWalletTypeException;
use App\PurchaseProducts\Strategy\Payment\Exceptions\PriceIsNotValidException;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\Setting;
use App\User;
use Exception;

/**
 * @method static create(array $array)
 * @method static where(string $string, int|null $authId)
 */
class Wallet implements PaymentInterface
{
    private $userId;
    private $walletType;
    private $cost;
    private $trackingCode;
    private $savedWallet = null;

    // wallet types;
    const INPUT = 'input';
    const OUTPUT = 'output';
    const REAGENT = 'reagent';
    const REAGENTED_CREATED_STORE = 'reagented_create_store';
    const REAGENTED = 'reagented';
    const DATE_GIFT = 'date_gift';
    const BUY_GIFT = 'buy_gift';
    const REGISTER_GIFT = 'register_gift';
    const REJECT_ORDER = 'reject_order';
    const BUY_AD = 'buy_ad';
    const BUY_PLAN = 'buy_plan';
    const FIRST_BUY_GIFT = "first_buy_gift";
    const FIRST_SELL_GIFT = "first_sell_gift";

    /*
     * request included below indices
     * user_id (This is only used for wallet payments)
     * wallet_type
     * cost
     * tracking_code
     * gateway (This is only used for online payments)
     * callback_url (This is only used for online payments)
     * */
    public function init($request)
    {
        $user = User::find($request['user_id']);
        if (!isset($request['user_id']) || !$user) {
            throw new UserNotPassedException();
        }
        $validWalletTypes = [
            self::INPUT,
            self::OUTPUT,
            self::REAGENT,
            self::REAGENTED_CREATED_STORE,
            self::REAGENTED,
            self::DATE_GIFT,
            self::BUY_GIFT,
            self::REGISTER_GIFT,
            self::REJECT_ORDER,
            self::BUY_AD,
            self::BUY_PLAN,
            self::FIRST_BUY_GIFT,
            self::FIRST_SELL_GIFT
        ];
        if (!isset($request['wallet_type']) || !in_array($request['wallet_type'], $validWalletTypes)) {
            throw new InvalidWalletTypeException();
        }
        if (!isset($request['tracking_code'])) {
            $trackingCode = null;
        } else {
            $trackingCode = $request['tracking_code'];
        }
        if (!isset($request['cost']) || !is_numeric($request['cost'])) {
            throw new PriceIsNotValidException();
        }
        $this->userId = $request['user_id'];
        $this->walletType = $request['wallet_type'];
        $this->trackingCode = $trackingCode;
        $this->cost = $request['cost'];
    }

    /**
     * @throws Exception
     */
    public function pay()
    {
        \DB::transaction(function () {
            $wallet = new \App\Wallet();
            $wallet->user_id = $this->userId;
            $wallet->cost = $this->cost;
            $wallet->wallet_type = $this->walletType;
            $wallet->tracking_code = $this->trackingCode;
            $wallet->save();

            $this->savedWallet = $wallet;

            //wallet reduce
            $walletHandler = new WalletHandler();

            $wallet = $walletHandler->NegativeRecordReducer($this->savedWallet);
            // $walletRestriction = Setting::pluck('wallet_restriction')->first();
            // $walletAllAvailableAmount = $walletHandler->getSumAvailableWalletRecords();

            // if ($walletAllAvailableAmount < (int)$walletRestriction) {
            //     throw new WalletRestriction();
            // }

            if ($wallet) {
                $this->savedWallet->reducedItem()->attach($wallet);
            } else {
                throw new Exception('موجودی ناکافی است');
            }
        });

    }

    public function verify()
    {
        return $this->savedWallet;
    }
}