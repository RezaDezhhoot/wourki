<?php

namespace App\Http\Controllers;

use App\AccountingDocuments;
use App\Address;
use App\Bill;
use App\BillItem;
use App\BillItemAttribute;
use App\Cart;
use App\CartAttribute;
use App\Discount;
use App\Events\BillConfirmed;
use App\Events\SMSHandler;
use App\Events\SubmitBill;
use App\Exciting_design;
use App\Guild;
use App\Http\Requests\web\filterBillRequest;
use App\Libraries\Swal;
use App\Process\PrNotification;
use App\Product_seller_attribute;
use App\ProductSeller;
use App\Province;
use App\PurchaseProducts\ChainOfResponsibility\CheckProductStock;
use App\PurchaseProducts\ChainOfResponsibility\CheckWalletStock;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\ProductStockIsNotEnough;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\UserIsBannedException;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\WalletStockIsNotEnough;
use App\PurchaseProducts\Decorator\CartDecorator;
use App\PurchaseProducts\Decorator\Exceptions\CartIsEmptyException;
use App\PurchaseProducts\Decorator\ShippingPrice;
use App\PurchaseProducts\Documents\DocumentHandler;
use App\PurchaseProducts\Exceptions\UserNotPassedException;
use App\PurchaseProducts\Exceptions\WalletRestriction;
use App\PurchaseProducts\Facade\Bill\BillTotalPriceCalculatorFacade;
use App\PurchaseProducts\Facade\Exceptions\AddressIsNotStoreInSessionException;
use App\PurchaseProducts\Facade\PaymentType\PaymentType;
use App\PurchaseProducts\Facade\SaveBillFacade;
use App\PurchaseProducts\Facade\VerifyPaymentFacade;
use App\PurchaseProducts\Strategy\Payment\Exceptions\CallbackUrlIsNotValidException;
use App\PurchaseProducts\Strategy\Payment\Exceptions\PriceIsNotValidException;
use App\PurchaseProducts\Strategy\Payment\Online;
use App\PurchaseProducts\Strategy\Payment\Payment;
use App\PurchaseProducts\Strategy\Shipping\Shipping;
use App\PurchaseProducts\Strategy\Shipping\Tehran;
use App\PurchaseProducts\Strategy\Shipping\Towns;
use App\Setting;
use App\Store;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Log;
use Morilog\Jalali\Jalalian;
use Throwable;

class BillController extends Controller
{

    public function adminConfirmIndex(filterBillRequest $request)
    {
        $request->validate([
            'confirmed' => ['required', 'in:0,1,2,all']
        ]);
        $stores = Store::where('status', 'approved')->select('id', 'name')->get();
        $products = ProductSeller::where('status', 'approved')->select('id', 'name')->get();
        $provinces = Province::all();
        $guilds = Guild::all();
        $billsInfo = Bill::join('store', 'store.id', '=', 'bill.store_id')
            ->join('users', 'users.id', '=', 'bill.user_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->join('address as buyer_address', 'buyer_address.id', '=', 'bill.address_id')
            ->join('city as buyer_city', 'buyer_city.id', '=', 'buyer_address.city_id')
            ->join('province as buyer_province', 'buyer_province.id', '=', 'buyer_city.province_id')
            ->join('address as seller_address', 'seller_address.id', '=', 'store.address_id')
            ->join('city as seller_city', 'seller_city.id', '=', 'seller_address.city_id')
            ->join('province as seller_province', 'seller_province.id', '=', 'seller_city.province_id')
            ->select('bill.*', 'store.name as store_name', 'guild.name as guild_name',
                'bill.pay_type', 'buyer_city.name as city_name', 'buyer_province.name as province_name')
            ->selectRaw('concat (users.first_name , " " , users.last_name) as full_name')
//            ->whereRaw('( bill.id NOT IN (select bill_id from accounting_documents) )')
            ->where('confirmed', $request->input('confirmed'));
        if ($request->input('confirmed') == 1) {
            $billsInfo->where('bill.status', 'pending');
        }

        if ($request->filled('province_buyer')) {
            $billsInfo->where('buyer_province.id', $request->province_buyer);
        }
        if ($request->filled('pay_type')) {
            $billsInfo->where('bill.pay_type', $request->pay_type);
        }
        if ($request->filled('city_buyer')) {
            $billsInfo->where('buyer_city.id', $request->city_buyer);
        }
        if ($request->filled('province_seller')) {
            $billsInfo->whereRaw('seller_province.id', $request->province_seller);
        }
        if ($request->filled('city_seller')) {
            $billsInfo->where('seller_city.id', $request->city_seller);
        }
        if ($request->filled('guild')) {
            $billsInfo->where('guild.id', $request->guild);
        }
        if ($request->filled('status')) {
            $billsInfo->where('bill.status', $request->status);
        } else
            $billsInfo->where('bill.status', '!=', 'rejected');
        if ($request->filled('store')) {
            $billsInfo->where('store.id', $request->store);
        }
        if ($request->filled('product')) {
            $billsInfo->whereRaw('((select id from product_seller) =' . $request->product . ')');
        }
        if ($request->filled('price_from') && $request->filled('price_to')) {
            $billsInfo->whereRaw('((select sum(bill_item.price) from bill_item group by bill_item.bill_id) >=' . $request->price_from . ')')
                ->whereRaw('((select sum(bill_item.price) from bill_item group by bill_item.bill_id) <=' . $request->price_to . ')');
        }
        if ($request->filled('start_date_ts') && $request->filled('end_date_ts')) {
            $fromDate = Carbon::createFromTimestamp($request->start_date_ts)->format('Y-m-d');
            $toDate = Carbon::createFromTimestamp($request->end_date_ts)->format('Y-m-d');

            $billsInfo->where('bill.created_at', '>=', $fromDate)
                ->where('bill.created_at', '<=', $toDate);
        }
        if ($request->filled('user')) {
            $billsInfo->where('bill.user_id', '=', $request->user);
        }

        $billsInfo = $billsInfo->orderBy('bill.id', 'desc')
            ->paginate(15)
            ->appends([
                'province_buyer' => $request->province_buyer,
                'pay_type' => $request->pay_type,
                'city_buyer' => $request->city_buyer,
                'province_seller' => $request->province_seller,
                'city_seller' => $request->city_seller,
                'guild' => $request->guild,
                'status' => $request->status,
                'store' => $request->store,
                'product' => $request->product,
                'price_from' => $request->price_from,
                'price_to' => $request->price_to,
                'start_date_ts' => $request->start_date_ts,
                'end_date_ts' => $request->end_date_ts,
            ]);

        $billItem = new BillItem();
        foreach ($billsInfo as $index => $row) {
            $billsInfo[$index]->billItems = $row->billItems;
            $billsInfo[$index]->billItemPrice = $billItem->getBillItemPrice($row->id);
        }
        return view('admin.bill.confirmIndex', compact('provinces', 'guilds', 'billsInfo', 'stores', 'products'));
    }

    public function adminConfirmBill(Request $request)
    {
        $request->validate([
            'billId' => 'required|array',
            'billId.*' => 'numeric|exists:bill,id',
        ], [
            'billId.*.numeric' => 'گزینه انتخابی نامعتبر است.',
            'billId.required' => 'هیچ موردی برای ثبت انتخاب نشده است.',
            'billId.exists' => 'گزینه انتخابی نامعتبر است.',
            'billId.array' => 'گزینه انتخابی نامعتبر است.',
        ]);
        Log::info('billIds :' . json_encode($request->billId));
        try {
            $commit = DB::transaction(function () use ($request) {
                $bills = Bill::whereIn('id' , $request->input('billId'))->get();
                event(new BillConfirmed($bills));
                $bills = Bill::whereIn('id', $request->input('billId'))->update(['confirmed' => 1]);
                return true;
            });

        } catch (\Exception $e) {
            Swal::success('اخطار', 'خطا در ثبت با پشتیبان تماس بگیرید.');
        }

        if ($commit == true) {
            $bills = Bill::whereIn('id', $request->input('billId'))->with('store.user')->get();
            $phoneNumber = [];
            foreach ($bills as $bill) {
                $phoneNumber[] = $bill->store->user->mobile;
            }
            event(new SMSHandler('bill', $phoneNumber));

            Swal::success('موفقیت آمیز.', 'سفارشات با موفقیت تایید شد.');
        }

        return redirect()->back();
    }

    /**
     * @throws Throwable
     */
    public function adminRejectBill(Request $request, Bill $bill)
    {
        if ($bill->confirmed == 2) {
            return redirect()->back();
        }
        $request->validate([
            'price' => 'required|numeric',
            'pay_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'reject_reason' => 'required|max:250',
            'reject_pay_type' => 'required|boolean',

        ], [
        ], [
            'price' => 'مبلغ',
            'pay_id' => 'شماره پیگیری',
            'date' => 'تاریخ',
            'reject_reason' => 'علت',
            'reject_pay_type' => 'روش واریز'

        ]);

        $bill->load(['user', 'store']);
        $data = $request->all();
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        $billItem = new BillItem();
        $storeName = $bill->store->name;
        $payId = $bill->pay_id == null ? '-' : $bill->pay_id;
        $billTotalPrice = $billItem->getBillItemPrice($bill->id);
        $returnPayTypeMessage = $data['reject_pay_type'] == 1 ? 'کیف پول' : 'حساب بانکی';

        $commit = DB::transaction(function () use ($bill, $request, $data, $date, $todayDate, $todayHour, $billTotalPrice, $storeName, $payId, $returnPayTypeMessage) {

            if ($billTotalPrice < $data['price']) {
                Swal::error('مبلغ', 'مبلغ واریزی بیشتر از مبلغ قابل پرداخت به خریدار میباشد');
                return false;
            }

            if ($data['reject_pay_type'] == 1) {
                //create wallet
                $wallet = Wallet::create([
                    'user_id' => $bill->user->id,
                    'cost' => $data['price'],
                    'wallet_type' => 'input',
                    'payable' => 1,
                    'comment' => 'بازگشت مبلغ ' . $data['price'] . ' بابت رد سفارش ' . $bill->id . ' - پیگیری : ' . $data['pay_id'] . ' - ساعت ' . $todayHour . ''
                ]);
            }

            AccountingDocuments::create(
                [
                    'balance' => $billTotalPrice,
                    'description' => 'خرید صورتحساب شماره ' . $bill->id . ' از' . $storeName . ' به مبلغ ' . $billTotalPrice . ' - شماره پیگیری ' . $payId .
                        ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour,
                    'bill_id' => $bill->id,
                    'type' => 'bill'
                ]
            );

            AccountingDocuments::create(
                [
                    'balance' => $data['price'],
                    'description' => 'واریز به ' . $returnPayTypeMessage . ' بابت صورتحساب شماره ' . $bill->id . ' از' . $storeName . ' به مبلغ ' . $data['price'] . ' - شماره پیگیری ' . $data['pay_id'] .
                        ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour,
                    'bill_id' => $bill->id,
                    'type' => 'checkout'
                ]
            );

            $bill->update([
                'status' => 'adminReject',
                'confirmed' => 2,
                'reject_reason' => $data['reject_reason'],
                'reject_pay_tracking_code' => $data['pay_id'],
                'reject_pay_date' => $date,
                'reject_pay_price' => $data['price'],
                'reject_pay_type' => $data['reject_pay_type']
            ]);

            return true;
        });


        if ($commit == true) {
            Swal::success('موفقیت آمیز بودن رد سفارش', 'سفارش با موفقیت رد شد');
        }

        return redirect()->back();
    }


    public function index(filterBillRequest $request)
    {
        $stores = Store::where('status', 'approved')->select('id', 'name')->get();
        $products = ProductSeller::where('status', 'approved')->select('id', 'name')->get();
        $provinces = Province::all();
        $guilds = Guild::all();
        $billsInfo = Bill::join('store', 'store.id', '=', 'bill.store_id')
            ->join('users', 'users.id', '=', 'bill.user_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->join('address as buyer_address', 'buyer_address.id', '=', 'bill.address_id')
            ->join('city as buyer_city', 'buyer_city.id', '=', 'buyer_address.city_id')
            ->join('province as buyer_province', 'buyer_province.id', '=', 'buyer_city.province_id')
            ->join('address as seller_address', 'seller_address.id', '=', 'store.address_id')
            ->join('city as seller_city', 'seller_city.id', '=', 'seller_address.city_id')
            ->join('province as seller_province', 'seller_province.id', '=', 'seller_city.province_id')
            ->select('bill.*', 'store.name as store_name', 'guild.name as guild_name',
                'bill.pay_type', 'buyer_city.name as city_name', 'buyer_province.name as province_name')
            ->selectRaw('concat (users.first_name , " " , users.last_name) as full_name')
            ->whereRaw('( bill.id NOT IN (select bill_id from accounting_documents) )')
            ->where('confirmed', 1);

        if ($request->filled('province_buyer')) {
            $billsInfo->where('buyer_province.id', $request->province_buyer);
        }
        if ($request->filled('pay_type')) {
            $billsInfo->where('bill.pay_type', $request->pay_type);
        }
        if ($request->filled('city_buyer')) {
            $billsInfo->where('buyer_city.id', $request->city_buyer);
        }
        if ($request->filled('province_seller')) {
            $billsInfo->whereRaw('seller_province.id', $request->province_seller);
        }
        if ($request->filled('city_seller')) {
            $billsInfo->where('seller_city.id', $request->city_seller);
        }
        if ($request->filled('guild')) {
            $billsInfo->where('guild.id', $request->guild);
        }
        if ($request->filled('status')) {
            $billsInfo->where('bill.status', $request->status);
        } else
            $billsInfo->where('bill.status', '!=', 'rejected');
        if ($request->filled('store')) {
            $billsInfo->where('store.id', $request->store);
        }
        if ($request->filled('product')) {
            $billsInfo->whereRaw('((select id from product_seller) =' . $request->product . ')');
        }
        if ($request->filled('price_from') && $request->filled('price_to')) {
            $billsInfo->whereRaw('((select sum(bill_item.price) from bill_item group by bill_item.bill_id) >=' . $request->price_from . ')')
                ->whereRaw('((select sum(bill_item.price) from bill_item group by bill_item.bill_id) <=' . $request->price_to . ')');
        }
        if ($request->filled('start_date_ts') && $request->filled('end_date_ts')) {
            $fromDate = Carbon::createFromTimestamp($request->start_date_ts)->format('Y-m-d');
            $toDate = Carbon::createFromTimestamp($request->end_date_ts)->format('Y-m-d');

            $billsInfo->where('bill.created_at', '>=', $fromDate)
                ->where('bill.created_at', '<=', $toDate);
        }
        if ($request->filled('user')) {
            $billsInfo->where('bill.user_id', '=', $request->user);
        }

        $billsInfo = $billsInfo->orderBy('bill.id', 'desc')
            ->paginate(15)
            ->appends([
                'province_buyer' => $request->province_buyer,
                'pay_type' => $request->pay_type,
                'city_buyer' => $request->city_buyer,
                'province_seller' => $request->province_seller,
                'city_seller' => $request->city_seller,
                'guild' => $request->guild,
                'status' => $request->status,
                'store' => $request->store,
                'product' => $request->product,
                'price_from' => $request->price_from,
                'price_to' => $request->price_to,
                'start_date_ts' => $request->start_date_ts,
                'end_date_ts' => $request->end_date_ts,
            ]);

        $billItem = new BillItem();
        foreach ($billsInfo as $index => $row) {
            $billsInfo[$index]->billItems = $row->billItems;
            $billsInfo[$index]->billItemPrice = $billItem->getBillItemPrice($row->id);
        }
        return view('admin.bill.all', compact('provinces', 'guilds', 'billsInfo', 'stores', 'products'));
    }

    public function makePainBack(Request $request)
    {
        $bill = Bill::find($request->id);
        $bill->status = 'paid_back';
        $bill->save();
    }

    public function invoiceDelete(Bill $bill)
    {
        if ($bill->status != 'delivered') {
            $bill->update(['status' => 'rejected']);

            if (in_array($bill->pay_type, ['online', 'wallet'])) {
                $totalPriceOfBillFacade = new BillTotalPriceCalculatorFacade($bill);
                $totalPriceOfBill = $totalPriceOfBillFacade->getTotalPrice();

                Wallet::create([
                    'user_id' => $bill->user_id,
                    'wallet_type' => 'reject_order',
                    'cost' => $totalPriceOfBill,
                ]);
            }

            return back();
        } else {
            Swal::error('خطا', 'شما نمی توانید فاکتور سفارش تحویل گرفته شده را لغو نمایید');
            return redirect()->back();
        }

    }

    public function salesDelete(Bill $bill)
    {
        $sumBillItemPrice = BillItem::where('bill_id', $bill->id)
            ->sum(\DB::raw('bill_item.quantity * ( bill_item.price - (bill_item.price * bill_item.discount / 100)) + bill_item.shipping_price'));
        $attributePrice = BillItemAttribute::join('bill_item', 'bill_item.id', '=', 'bill_item_attribute.bill_item_id')
            ->join('bill', 'bill.id', '=', 'bill_item.bill_id')
            ->where('bill.id', '=', $bill->id)
            ->sum('bill_item_attribute.extra_price');
        $totalPrice = $sumBillItemPrice + $attributePrice;


        $wallet = new Wallet();
        $wallet->user_id = $bill->user_id;
        $wallet->cost = $totalPrice;
        $wallet->wallet_type = 'reject_order';
        $wallet->tracking_code = null;
        $wallet->save();


        $bill->update(['status' => 'rejected']);
        return back();
    }

    public function makeInvoiceDelivered(Bill $bill): \Illuminate\Http\RedirectResponse
    {
        $bill->load('billItems');
        try {
            foreach (Exciting_design::all() as $item) {
                if (Carbon::createFromFormat('Y-m-d H:i:s', $bill->created_at) >= Carbon::createFromFormat('Y-m-d H:i:s', $item->from_date) &&
                    Carbon::createFromFormat('Y-m-d H:i:s', $bill->created_at) <= Carbon::createFromFormat('Y-m-d H:i:s', $item->to_date)) {
                    $bill->billItems->each(function ($billItem) {
                        $billItem->totalPrice = ($billItem->price - (($billItem->price * $billItem->discount) / 100)) * $billItem->quantity;
                        $billItem->totalPrice += $billItem->attributes->sum('extra_price');
                    });
                    $sumPrice = $bill->billItems->sum('totalPrice');
                    if ($sumPrice >= $item->price) {
                        Wallet::create([
                            'user_id' => auth()->guard('web')->user()->id,
                            'cost' => $item->gift,
                            'wallet_type' => 'date_gift'
                        ]);
                        Swal::success('تبریک.', 'خریدار گرامی، مبلغ ' . $item->gift . ' تومان بابت طرح تشویقی خرید شما به کیف پول شما اضافه گردید.');
                    }
                    break;
                }
            }/*end foreach*/
            $result = new DocumentHandler([$bill]);
            $result->submitBillDocument();

        } catch (Exception $e) {
            // Swal::error('اخطار', 'خطا در ثبت با پشتیبان تماس بگیرید.');
            Swal::error('اخطار', $e->getMessage());
        }

        return back();
    }

    public function makeSalesDelivered(Bill $bill)
    {
        $bill->update(['status' => 'delivered']);
        return back();
    }

    public function purchaseInvoice()
    {
        $user = auth()->guard('web')->user();
        $bills = Bill::where('bill.user_id', $user->id)
            ->join('store', 'store.id', '=', 'bill.store_id')
            ->join('address', 'address.id', '=', 'store.address_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->select('bill.*', 'address.address as store_address', 'province.name as province_name', 'city.name as city_name')
            ->orderByDesc('bill.created_at')
            ->paginate(20);
        $billItem = new BillItem();

        foreach ($bills as $index => $bill) {
            $bills[$index]->store = $bill->store->name;
            $bills[$index]->billItemss = $bill->billItems;
            $bills[$index]->address = Address::where('id', $bill->address_id)->first()->address;
            $bills[$index]->total_price = $billItem->getBillItemPrice($bill->id);
        };
        return view('frontend.my-account.bills.purchase', compact('bills'));
    }

    public function salesInvoice()
    {
        $user = auth()->guard('web')->user();
        $bills = null;
        $billItem = new BillItem();

        if (count($user->stores) > 0) {
            $stores = Store::where('user_id' , $user->id)->pluck('id');
            $bills = Bill::whereIn('bill.store_id', $stores)
                ->join('address', 'address.id', '=', 'bill.address_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->join('users', 'users.id', '=', 'bill.user_id')
                ->orderByDesc('bill.created_at')
                ->select('bill.*', 'province.name as province_name', 'city.name as city_name', 'users.first_name', 'users.last_name')
                ->paginate(20);
            foreach ($bills as $index => $bill) {
                $bills[$index]->store = $bill->store->name;
                $bills[$index]->billItemss = $bill->billItems;
                $bills[$index]->total_price = $billItem->getBillItemPriceWithCommission($bill->id);
            };
        }

        return view('frontend.my-account.bills.sales', compact('bills'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|numeric|exists:address,id',
            'pay_type' => 'required|in:wallet,online',
        ]);
        $user = auth()->guard('web')->user();
        try {
            $paymentType = $request->pay_type == 'wallet' ?
                new PaymentType(new \App\PurchaseProducts\Facade\PaymentType\Wallet())
                : new PaymentType(new \App\PurchaseProducts\Facade\PaymentType\Online());
            $discount = Discount::where('id' , $request->discount)
            ->whereNotIn('discountable_type' , ['all-ads' , 'all-plans' , 'ad' , 'plan' , 'upgrade' , 'all-upgrade' , 'all-sending' , 'store-sending' , 'product-sending' ])
            ->first();
            if($request->discount_code && $request->discount_code != '' && !$discount){
                Swal::error('خطا!', 'کد تخفیف وارد شده معتبر نیست');
                return back();
            }
            $savedBill = new SaveBillFacade($user->id, $request->address, $paymentType , $discount);
            return $savedBill->save();
        } catch (UserIsBannedException $bannedException) {
            Swal::error('خطا!', 'حساب کاربری شما مسدود شده است. بنابراین شما قادر به خرید نیستید.');
            return back();
        } catch (WalletStockIsNotEnough $exception) {
            Swal::error('خطا!', 'موجودی کیف پول شما کمتر از میزان مورد نیاز است.');
            return back();
        } catch (ProductStockIsNotEnough $exception) {
            Swal::error('خطا!', 'موجودی انبار حداقل یکی از محصولات از موجودی درخواستی کمتر است.');
            return back();
        } catch (CartIsEmptyException $exception) {
            Swal::error('خطا!', 'سبد خرید خالی است.');
            return back();
        } catch (UserNotPassedException $exception) {
            Swal::error('خطا' , 'error');
            return redirect()->back();
        } catch (WalletRestriction $exception) {
            Swal::error('محدودیت کیف پول', 'مبلغ خرید کمتر از حداقل خرید به وسیله کیف پول میباشد.');
            return redirect()->back();
        }
        catch(Throwable $e){
            Swal::error('خطا', $e->getMessage());
            return redirect()->back();
        }
        Swal::success('تبریک!', 'فاکتور برای شما ثبت شد شما میتوانید وضعیت خریدتان را اینجا بررسی کنید.');
        return redirect()->route('user.purchase.invoice');

    }

    public function calcUserCartPrice()
    {
        $user = auth()->guard('web')->user();
        $cartItems = $user->carts;
        $total = 0;
        // loop over the cart items of user
        foreach ($cartItems as $carItem) {
            // get all attributes of cart of the user
            $userCartAttrs = CartAttribute::where('cart_id', $carItem->id)->get();
            $attrPrice = 0;
            if (count($userCartAttrs) > 0) {
                foreach ($userCartAttrs as $cartItemAttr) {
                    $extraPrice = Product_seller_attribute::find($cartItemAttr->product_seller_attribute_id)->extra_price;
                    $attrPrice += $extraPrice;
                }
            }
            $price = $this->calcProductDiscount($carItem->product->id) * $carItem->quantity + $attrPrice;
            $total = $price + $total;
        }
        return $total;
    }

    public function calcProductDiscount($product)
    {
        $product = ProductSeller::find($product);
        $price = $product->price - (($product->price * $product->discount) / 100);
        return $price;
    }

    public function verifyCart(Request $request)
    {
        try {
            $verifyPaymentFacade = new VerifyPaymentFacade();
            $verifyPaymentFacade->verifyPayment();
            Swal::success('تبریک!', 'فاکتور برای شما ثبت شد شما میتوانید وضعیت خریدتان را اینجا بررسی کنید.');
            return redirect()->route('user.purchase.invoice');
        } catch (AddressIsNotStoreInSessionException $exception) {
            echo $exception->getMessage();
        } catch (RetryException $e) {
            echo $e->getMessage();
        } catch (PortNotFoundException $e) {
            echo $e->getMessage();
        } catch (InvalidRequestException $e) {
            echo $e->getMessage();
        } catch (NotFoundTransactionException $e) {
            echo $e->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
