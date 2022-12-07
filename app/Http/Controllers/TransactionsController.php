<?php

namespace App\Http\Controllers;

use App\AccountingDocuments;
use App\Ads;
use App\AdsPosition;
use App\Bill;
use App\BillItem;
use App\Guild;
use App\ProductSeller;
use App\Province;
use App\Store;
use App\Upgrade;
use App\UpgradePosition;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $list = DB::table('gateway_transactions')
            ->select('*');
        if ($request->filled('tracking_code')) {
            $list->where('tracking_code', 'like', "%" . $request->tracking_code . "%");
        }
        if ($request->filled('ref_id')) {
            $list->where('ref_id', 'like', "%" . $request->ref_id . "%");
        }
        $list = $list
            ->orderBy('payment_date' , 'desc')
            ->paginate(20);
        foreach($list as $index => $row){
            $logs = DB::table('gateway_transactions_logs')
                ->select('*')
                ->where('transaction_id' , '=' , $row->id)
                ->get();
            $list[$index]->logs = $logs;
        }

        return view('admin.transactions.index' , compact('list'));
    }
    public function productUpgrades(Request $request){
        $upgrades = Upgrade::where('upgradable_type' , ProductSeller::class)->where('upgrades.pay_type' , 'online')
            ->join('product_seller' , 'upgradable_id' , '=' , 'product_seller.id')
            ->join('store' , 'product_seller.store_id' , '=' , 'store.id')
            ->join('users' , 'store.user_id' , '=' , 'users.id')
            ->join('upgrade_positions' , 'upgrade_position_id' , '=' , 'upgrade_positions.id')
            ->select('upgrades.*' , 'users.id as user_id' , 'users.first_name' , 'users.last_name' , 'product_seller.name' , 'upgrade_positions.name as position_name')
            ->orderByDesc('updated_at');
        if($request->filled('tracking_code')){
            $upgrades->where('tracking_code' , $request->tracking_code);
        }
        if($request->filled('user_id') && $request->user_id != 0){
            $upgrades->where('users.id' , $request->user_id);
        }
        if($request->filled('store_id') && $request->store_id != 0){
            $upgrades->where('store.id' , $request->store_id);
        }
        if($request->filled('position_id') && $request->position != 0){
            $upgrades->where('upgrade_position_id' , $request->position_id);
        }
        $upgrades = $upgrades->paginate(30);
        $users = User::all();
        $stores = Store::all();
        $positions = UpgradePosition::all();
        return view('admin.transactions.upgrades' , compact('upgrades' , 'users' , 'stores' , 'positions'));
    }
    public function storeUpgrades(Request $request){
        $upgrades = Upgrade::where('upgradable_type', Store::class)->where('upgrades.pay_type', 'online')->join('store', 'upgradable_id', '=', 'store.id')
            ->join('users', 'store.user_id', '=', 'users.id')
            ->join('upgrade_positions', 'upgrade_position_id', '=', 'upgrade_positions.id')
            ->select('upgrades.*', 'users.id as user_id', 'users.first_name', 'users.last_name' , 'store.name' , 'upgrade_positions.name as position_name')
            ->orderByDesc('updated_at');
        if ($request->filled('tracking_code')) {
            $upgrades->where('tracking_code', $request->tracking_code);
        }
        if ($request->filled('user_id')) {
            $upgrades->where('users.id', $request->user_id);
        }
        if ($request->filled('store_id')) {
            $upgrades->where('store.id', $request->store_id);
        }
        if ($request->filled('position_id')) {
            $upgrades->where('upgrade_position_id', $request->position_id);
        }
        $upgrades = $upgrades->paginate(30);
        $users = User::all();
        $stores = Store::all();
        $positions = UpgradePosition::all();
        return view('admin.transactions.upgrades', compact('upgrades' , 'stores' , 'users' , 'positions'));
    }
    public function ads(Request $request){
        if($request->query('type') == 'product'){
            $ads = Ads::where('link_type', 'product')
                ->join('product_seller', 'ads.product_id', '=', 'product_seller.id')
                ->join('store', 'product_seller.store_id', '=', 'store.id')
                ->join('users', 'store.user_id', '=', 'users.id')
                ->join('ads_position', 'ads_position_id', '=', 'ads_position.id')
                ->join('ads_stairs', 'ads.id', '=', 'ads_stairs.ads_id')
                ->select('ads.*', 'users.id as user_id', 'users.first_name', 'users.last_name', 'product_seller.name', 'ads_position.name as position_name', 'ads_stairs.tracking_code', 'ads_stairs.price','ads_stairs.initial_pay' , 'ads_stairs.updated_at as updated_date')
                // ->where('ads_stairs.payment_type', 'online')
                ->orderByDesc('ads_stairs.updated_at');
        }
        else{

            $ads = Ads::where('link_type', 'store')
            ->join('store', 'ads.store_id', '=', 'store.id')
            ->join('users', 'store.user_id', '=', 'users.id')
            ->join('ads_position', 'ads_position_id', '=', 'ads_position.id')
            ->join('ads_stairs', 'ads.id', '=', 'ads_stairs.ads_id')
            ->select('ads.*', 'users.id as user_id', 'users.first_name', 'users.last_name', 'store.name', 'ads_position.name as position_name', 'ads_stairs.tracking_code', 'ads_stairs.price', 'ads_stairs.initial_pay' , 'ads_stairs.updated_at as updated_date')
            // ->where('ads_stairs.payment_type', 'online')
            ->orderByDesc('ads_stairs.updated_at');

        }
        if ($request->filled('show') && $request->show != 'all') {
            $ads->where('ads_stairs.initial_pay', ($request->show == 'self' ? 'initial' : 'stairs'));
        }
        if ($request->filled('user_id') && $request->user_id != 0) {
            $ads->where('users.id', $request->user_id);
        }
        if ($request->filled('store_id') && $request->store_id != 0) {
            $ads->where('store.id', $request->store_id);
        }
        if ($request->filled('position_id') && $request->position_id != 0) {
            $ads->where('ads_position_id', $request->position_id);
        }
        $ads = $ads->paginate(30);
        $users = User::all();
        $stores = Store::all();
        $positions = AdsPosition::all();
        return view('admin.transactions.ads', compact('ads', 'stores', 'users', 'positions'));
    }
    public function commisions(Request $request){
        $docs = AccountingDocuments::where('is_commision' , true)
        ->whereNot('bill_id' , 0)
        ->join('bill' , 'bill_id' , '=' , 'bill.id')
        ->join('store' , 'bill.store_id' , '=' , 'store.id')
        ->join('users' , 'store.user_id' , '=' , 'users.id')
        ->select('accounting_documents.*' , 'users.first_name' , 'users.last_name' , 'store.name as store_name');
        if($request->filled('user_id') && $request->user_id != 0){
            $docs->where('users.id' , $request->user_id);
        }
        if($request->filled('store_id') && $request->store_id != 0){
            $docs->where('bill.store_id' , $request->store_id);
        }
        $docs = $docs->paginate(20);
        $users = User::all();
        $stores = Store::all();
        return view('admin.transactions.commisions' , compact('docs' , 'users' , 'stores'));
    }
    public function orders(Request $request){
        $stores = Store::where('status', 'approved')->select('id', 'name')->get();
        $products = ProductSeller::where('status', 'approved')->select('id', 'name')->get();
        $provinces = Province::all();
        $guilds = Guild::all();
        $billsInfo = Bill::join('store', 'store.id', '=', 'bill.store_id')
        ->where('bill.status' , '!=' , 'delivered')
        ->join('users', 'users.id', '=', 'bill.user_id')
        ->join('guild', 'guild.id', '=', 'store.guild_id')
        ->join('address as buyer_address', 'buyer_address.id', '=', 'bill.address_id')
        ->join('city as buyer_city', 'buyer_city.id', '=', 'buyer_address.city_id')
        ->join('province as buyer_province', 'buyer_province.id', '=', 'buyer_city.province_id')
        ->join('address as seller_address', 'seller_address.id', '=', 'store.address_id')
        ->join('city as seller_city', 'seller_city.id', '=', 'seller_address.city_id')
        ->join('province as seller_province', 'seller_province.id', '=', 'seller_city.province_id')
        ->select(
            'bill.*',
            'store.name as store_name',
            'guild.name as guild_name',
            'bill.pay_type',
            'buyer_city.name as city_name',
            'buyer_province.name as province_name'
        )
        ->selectRaw('concat (users.first_name , " " , users.last_name) as full_name');
        //            ->whereRaw('( bill.id NOT IN (select bill_id from accounting_documents) )')

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
        return view('admin.bill.statistics', compact('provinces', 'guilds', 'billsInfo', 'stores', 'products'));
    }
}
