<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Events\UpgradeCreated;
use App\Libraries\Swal;
use App\ProductSeller;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\Store;
use App\Upgrade;
use App\UpgradePosition;
use App\UsedDiscount;
use App\User;
use Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;

class UpgradeController extends Controller
{
    public function positionsPage(Request $request){
        $positions = UpgradePosition::all();
        return view('admin.upgrade.positions' , compact('positions'));
    }
    public function positionsUpdate(Request $request){
        $prices = $request->all();
        DB::beginTransaction();
        try{
        foreach($prices as $key => $val){
            if(!str_contains($key , 'price'))
                continue;
            $id = explode('_' , $key)[1];
            UpgradePosition::query()->where('id' , $id)->update(['price' => $val]);
            
        }
        DB::commit();
        Swal::success('موفقیت آمیز.', 'لیست قیمت ها با موفقیت آپدیت شد');
        return redirect()->back();
        }
        catch(Throwable $e){
            DB::rollBack();
            return $e->getMessage();
            Swal::error('خطا', 'هنگام به روزرسانی قیمت ها مشکلی به وجود آمد');
            return redirect()->back();
        }
    }
    public function upgradeProduct(Request $request){
        $request->validate([
            'position_id' => 'required|exists:upgrade_positions,id',
            'product_seller_id' => 'required|exists:product_seller,id',
            'wallet' => 'string',
            'discount' => 'nullable|exists:discounts,id'
        ]);
        $position = UpgradePosition::find($request->position_id);
        $price = $position->price;
        $product = ProductSeller::find($request->product_seller_id);
        $user = $product->store->user;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'upgrade', $position->id);
            if (!is_null($discount)) {
                $price = $discount->applyOn($price);
            }
        }
        $storeType = $product->store->store_type;
        if(!str_contains($position->position , $storeType)){
            Swal::error('خطا' , 'جایگاه انتخابی با آیتم انتخاب شده مطابقت ندارد');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            if($request->wallet){
            if ($user->wallet()->sum('cost') < $price) {
                Swal::error('خطا', 'موجودی کیف پول شما کمتر از مبلغ مورد نظر است.');
                return redirect()->back();
            }
            if ($price != 0) {
                $wallet = $user->wallet()->create([
                    'cost' => -1 * $price,
                    'wallet_type' => 'upgrade_product'
                ]);
                $walletHandler = new WalletHandler();
                if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
                    $wallet->reducedItem()->attach($data);
                }
                
            }
        }
            $upgrade = new Upgrade();
            $upgrade->upgrade_position_id = $request->position_id;
            $upgrade->upgradable_type = ProductSeller::class;
            $upgrade->upgradable_id = $product->id;
            $upgrade->status = 'approved';
            $upgrade->pay_type = 'admin';
            $upgrade->price = $price;
            $upgrade->save();
            event(new UpgradeCreated($upgrade));
            DB::commit();
            Swal::success('موفقیت آمیز.', 'با موفقیت ارتقا یافت');
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            Swal::error('خطا', 'مشکلی به وجود آمده است');
            return redirect()->back();
        }
    }
    public function upgradeStore(Request $request){
        $request->validate([
            'position_id' => 'required|exists:upgrade_positions,id',
            'store_id' => 'required|exists:store,id',
            'wallet' => 'string',
            'discount' => 'nullable|exists:discounts,id'
        ]);
        $position = UpgradePosition::find($request->position_id);
        $store = Store::find($request->store_id);
        $price = $position->price;
        $user = $store->user;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'upgrade', $position->id);
            if (!is_null($discount)) {
                $price = $discount->applyOn($price);
            }
        }


        if (!str_contains($position->position, 'store')) {
            Swal::error('خطا', 'جایگاه انتخابی با آیتم انتخاب شده مطابقت ندارد');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            if($request->wallet){
            if ($user->wallet()->sum('cost') < $price) {
                Swal::error('خطا', 'موجودی کیف پول کمتر از مبلغ مورد نظر است.');
                return redirect()->back();
            }
            if ($price != 0) {
                $wallet = $user->wallet()->create([
                    'cost' => -1 * $price,
                    'wallet_type' => 'upgrade_product'
                ]);
                $walletHandler = new WalletHandler();
                if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
                    $wallet->reducedItem()->attach($data);
                }
            }
        }
            $upgrade = new Upgrade();
            $upgrade->upgrade_position_id = $request->position_id;
            $upgrade->upgradable_type = Store::class;
            $upgrade->upgradable_id = $store->id;
            $upgrade->status = 'approved';
            $upgrade->pay_type = 'admin';
            $upgrade->price = $price;
            $upgrade->save();
            event(new UpgradeCreated($upgrade));
            DB::commit();
            Swal::success('موفقیت آمیز.', 'با موفقیت ارتقا یافت');
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            Swal::error('خطا', 'مشکلی به وجود آمده است');
            return redirect()->back();
        }
    }
    public function upgradeProductUser(Request $request){
        $request->validate([
            'position_id' => 'required|exists:upgrade_positions,id',
            'product_seller_id' => 'required|exists:product_seller,id',
            'wallet' => 'string',
            'discount' => 'nullable|exists:discounts,id'

        ]);
        $product = ProductSeller::find($request->product_seller_id);
        $position = UpgradePosition::find($request->position_id);
        $price = $position->price;
        $user = auth()->guard('web')->user();
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'upgrade', $position->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $price;
                $price = $discount->applyOn($price);
                $usedDiscount->price_with_discount = $price;
            }
        }
        
        $storeType = $product->store->store_type;
        if (!str_contains($position->position, $storeType)) {
            Swal::error('خطا', 'جایگاه انتخابی با آیتم انتخاب شده مطابقت ندارد');
            return redirect()->back();
        }
        $user = User::find($user->id);
        if ($request->wallet) {
                DB::beginTransaction();
                try{
                if ($user->wallet()->sum('cost') < $price) {
                    Swal::error('خطا', 'موجودی کیف پول شما کمتر از مبلغ مورد نظر است.');
                    return redirect()->back();
                }
                if($price != 0){
                $wallet = $user->wallet()->create([
                    'cost' => -1 * $price,
                    'wallet_type' => 'upgrade_product'
                ]);
                $walletHandler = new WalletHandler();
                if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
                    $wallet->reducedItem()->attach($data);
                }
            }
                $upgrade = new Upgrade();
                $upgrade->upgrade_position_id = $request->position_id;
                $upgrade->upgradable_type = ProductSeller::class;
                $upgrade->upgradable_id = $product->id;
                $upgrade->status = 'approved';
                $upgrade->pay_type = 'wallet';
                $upgrade->price = $price;
                if($request->has('from_marketer')){
                    $upgrade->from_marketer = optional(Store::where('store_type' , 'market')->where('user_id' , $user->id)->first())->id;
                }
                $upgrade->save();
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'wallet';
                    $usedDiscount->save();
                }
                event(new UpgradeCreated($upgrade));
                DB::commit();
                Swal::success('موفقیت آمیز.', 'با موفقیت ارتقا یافت');
                return redirect()->back();
            }
            catch(Throwable $e){
                DB::rollBack();
                Swal::error('خطا' , 'مشکلی به وجود آمده است');
                return redirect()->back();
            }
        }
        else{
            //sending user to zarinpal
            $upgrade = new Upgrade();
            $upgrade->upgrade_position_id = $request->position_id;
            $upgrade->upgradable_type = ProductSeller::class;
            $upgrade->upgradable_id = $product->id;
            $upgrade->status = 'pending';
            $upgrade->pay_type = 'online';
            $upgrade->price = $price;
            if ($request->has('from_marketer')) {
                $upgrade->from_marketer = optional(Store::where('store_type' , 'market')->where('user_id' , $user->id)->first())->id;
            }
            $upgrade->save();
            if($price > 100){
            session()->put('upgrade_id', $upgrade->id);
            session()->put('is_service' , $product->store->store_type == "service");
            $gateway = Gateway::zarinpal();
            $gateway->setCallback(route('verify.upgradepay'));
            $gateway
                ->price($price * 10)
                ->ready();
            if ($usedDiscount) {
                $usedDiscount->pay_type = 'online';
                $usedDiscount->status = 'pending';
                $usedDiscount->save();
                $request->session()->put('used_discount_id', $usedDiscount->id);
            }
            return $gateway->redirect();
            } else {
                $upgrade->status = 'approved';
                $upgrade->save();
                Swal::success('موفقیت آمیز.', 'با موفقیت ارتقا یافت');
                return redirect()->back();
            }
        }
    }
    public function verifyUpgradePay(Request $request){
        $usedDiscount = UsedDiscount::find($request->session()->get('used_discount_id'));
        try {
            \DB::beginTransaction();
            $upgrade = Upgrade::find($request->session()->get('upgrade_id'));
            session()->forget('upgrade_id');
            if (!$upgrade) {
                return 'مشکلی در پرداخت شما به وجود آمده است';
            }
            $upgrade->status = 'approved';
            $gateway = Gateway::verify();
            if ($usedDiscount) {
                $usedDiscount->status = 'approved';
                $usedDiscount->save();
            }
            $trackingCode = $gateway->trackingCode();
            $upgrade->tracking_code = $trackingCode;
            $upgrade->save();
            event(new UpgradeCreated($upgrade));
            \DB::commit();
            Swal::success('تبریک!', 'پرداخت شما با موفقیت انجام شد');
            if(session()->has('is_store')){
                session()->forget('is_store');
                return redirect()->route('create.store.page');
            }
            else{
                session()->forget('is_service');
                return redirect()->route($request->session()->get('is_service') ? 'user.services' : 'user.products');
            }
                

        } catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage();
        }
    }
    public function upgradeStoreUser(Request $request){
        $request->validate([
            'position_id' => 'required|exists:upgrade_positions,id',
            'store_id' => 'required|exists:store,id',
            'wallet' => 'string',
            'discount' => 'nullable|exists:discounts,id'
        ]);
        $store = Store::find($request->store_id);
        $position = UpgradePosition::find($request->position_id);
        $price = $position->price;
        $user = auth()->guard('web')->user();
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'upgrade', $position->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $price;
                $price = $discount->applyOn($price);
                $usedDiscount->price_with_discount = $price;
            }
        }
        if (!str_contains($position->position, 'store')) {
            Swal::error('خطا', 'جایگاه انتخابی با آیتم انتخاب شده مطابقت ندارد');
            return redirect()->back();
        }

        $user = User::find($user->id);
        if ($request->wallet) {
            DB::beginTransaction();
            try {
                if ($user->wallet()->sum('cost') < $price) {
                    Swal::error('خطا', 'موجودی کیف پول شما کمتر از مبلغ مورد نظر است.');
                    return redirect()->back();
                }
                if($price != 0){
                $wallet = $user->wallet()->create([
                    'cost' => -1 * $price,
                    'wallet_type' => 'upgrade_store'
                ]);
                $walletHandler = new WalletHandler();
                if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
                    $wallet->reducedItem()->attach($data);
                }
            }
                $upgrade = new Upgrade();
                $upgrade->upgrade_position_id = $request->position_id;
                $upgrade->upgradable_type = Store::class;
                $upgrade->upgradable_id = $store->id;
                $upgrade->status = 'approved';
                $upgrade->pay_type = 'wallet';
                $upgrade->price = $price;
                if ($request->has('from_marketer')) {
                    $upgrade->from_marketer = optional(Store::where('store_type' , 'market')->where('user_id' , $user->id)->first())->id;
                }
                $upgrade->save();
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'wallet';
                    $usedDiscount->save();
                }
                event(new UpgradeCreated($upgrade));
                DB::commit();
                Swal::success('موفقیت آمیز.', 'با موفقیت ارتقا یافت');
                return redirect()->back();
            } catch (Throwable $e) {
                DB::rollBack();
                Swal::error('خطا', $e->getMessage());
                return redirect()->back();
            }
        } else {
            //sending user to zarinpal
            $upgrade = new Upgrade();
            $upgrade->upgrade_position_id = $request->position_id;
            $upgrade->upgradable_type = Store::class;
            $upgrade->upgradable_id = $store->id;
            $upgrade->status = 'pending';
            $upgrade->pay_type = 'online';
            $upgrade->price = $price;
            if ($request->has('from_marketer')) {
                $upgrade->from_marketer = optional(Store::where('store_type' , 'market')->where('user_id' , $user->id)->first())->id;
            }
            $upgrade->save();
            if($price > 100){
            session()->put('upgrade_id', $upgrade->id);
            session()->put('is_store', true);
            $gateway = Gateway::zarinpal();
            $gateway->setCallback(route('verify.upgradepay'));
            $gateway
                ->price($price * 10)
                ->ready();
            if ($usedDiscount) {
                $usedDiscount->pay_type = 'online';
                $usedDiscount->status = 'pending';
                $usedDiscount->save();
                $request->session()->put('used_discount_id', $usedDiscount->id);
            }
            return $gateway->redirect();
            }
            else{
                $upgrade->status = 'approved';
                $upgrade->save();
                Swal::success('موفقیت آمیز.', 'با موفقیت ارتقا یافت');
                return redirect()->back();
            }
        }
    }
}
