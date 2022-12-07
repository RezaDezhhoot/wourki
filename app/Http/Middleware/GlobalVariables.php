<?php

namespace App\Http\Middleware;

use App\AccountingDocuments;
use App\Ads;
use App\Cart;
use App\Category;
use App\Guild;
use App\Message;
use App\ProductPhoto;
use App\Products;
use App\Wallet;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class GlobalVariables
{
    public function handle($request, Closure $next)
    {
        if(auth()->guard('admin')->check()) {
            $totalWallet = Wallet::join('users' , 'users.id' , '=' , 'wallet.user_id')
                ->select('wallet.*' , 'users.first_name' , 'users.last_name')
                ->where('tracking_code' , '!=' , null)
                ->whereNotIn('wallet.id', AccountingDocuments::where('wallet_id' , '!=' , 0)->select('wallet_id')->get()->toArray())
                ->count();
            view()->share('total_wallet' , $totalWallet);
            $submittedAccountingDocumentsCount = DB::table('bill')
                ->join('accounting_documents as acc_doc' , 'acc_doc.bill_id' , '=' , 'bill.id')
                ->where('acc_doc.type' , '=' , 'bill')
                ->where('bill.status' , '=' , 'delivered')
                ->where('bill.pay_type' , '=' , 'online')
                ->whereRaw('(
                    (
                        IFNULL(
                        (
                            select sum(bill_item.quantity * (bill_item.price - (bill_item.price * bill_item.discount / 100) ) )
                            from bill_item
                            where bill_item.bill_id = bill.id
                        ) , 0)
                         +
                         IFNULL(
                         (
                            select sum(extra_price)
                            from bill_item_attribute
                            join bill_item as bi3 on bi3.id = bill_item_attribute.bill_item_id
                            where bi3.bill_id = bill.id
                         ) , 0)
                    )     
                    >
                    IFNULL(
                    (
                        select sum(price)
                        from checkouts
                        where store_id = bill.store_id
                    ) , 0)
                )')
                ->count();
            view()->share('submittedAccountingDocumentsCount' , $submittedAccountingDocumentsCount);

            $newSupportTicketsCount = Message::whereNotNull('message.user_id')->whereNull('message.receiver_id')
                ->join('support_last_message' , 'support_last_message.user_id' , '=' , 'message.user_id')
                ->where('message.view' , 0)->count();
            view()->share('newSupportTicketCount' , $newSupportTicketsCount);
        }

        $guildAndCategories = Guild::with(['categories'])
            ->get();
        view()->share('guildAndCategoriesForMegaMenu' , $guildAndCategories);


        $countOfPedningAds = Ads::where('status' , 'pending')->count();
        view()->share('countOfPedningAds' , $countOfPedningAds);

        $user = auth()->guard('web')->user();
        view()->share('auth_web_user' , $user);
        return $next($request);
    }
}
