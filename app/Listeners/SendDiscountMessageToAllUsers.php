<?php

namespace App\Listeners;

use App\AdsPosition;
use App\Category;
use App\Events\DiscountSaved;
use App\Guild;
use App\Message;
use App\Plan;
use App\ProductSeller;
use App\Setting;
use App\Store;
use App\SupportLastMessage;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class SendDiscountMessageToAllUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DiscountSaved  $event
     * @return void
     */
    public function handle(DiscountSaved $event)
    {
        $discount = $event->discount;
        if($discount->discountable_type == 'all'){
            $apply_on = 'تمامی امکانات سایت';
        }
        if($discount->discountable_type == 'all-service'){
            $apply_on = 'تمامی خدمات';
        }
        if($discount->discountable_type == 'all-product'){
            $apply_on = 'تمامی محصولات';
        }
        if($discount->discountable_type == 'all-ads'){
            $apply_on = 'تمامی تبلیغات';
        }
        if($discount->discountable_type == 'all-plans'){
            $apply_on = 'تمامی اشتراک ها';
        }
        if($discount->discountable_type == 'product'){
            $apply_on = 'محصول ' . ProductSeller::find($discount->discountable_id)->name;
        }
        if($discount->discountable_type == 'service'){
            $apply_on = 'خدمت ' . ProductSeller::find($discount->discountable_id)->name;
        }
        if($discount->discountable_type == 'plan'){
            $apply_on = 'اشتراک '. Plan::find($discount->discountable_id)->plan_name;
        }
        if($discount->discountable_type == 'ad'){
            $apply_on = 'تبلیغ '. AdsPosition::find($discount->discountable_id)->name;
        }
        if($discount->discountable_type == 'guild'){
            $apply_on = 'صنف ' . Guild::find($discount->discountable_id)->name;
        }
        if($discount->discountable_type == 'category'){
            $apply_on = 'دسته بندی ' . Category::find($discount->discountable_id)->name;
        }
        if ($discount->discountable_type == 'store') {
            $apply_on = 'فروشگاه ' . Store::find($discount->discountable_id)->name;
        }
        if ($discount->discountable_type == 'all-sending') {
            $apply_on = 'همه ارسال ها ';
        }
        if ($discount->discountable_type == 'store-sending') {
            $apply_on = 'ارسال های فروشگاه ' . Store::find($discount->discountable_id)->name;
        }
        if ($discount->discountable_type == 'product-sending') {
            $apply_on = 'ارسال های محصول/خدمت ' . ProductSeller::find($discount->discountable_id)->name;
        }
        $setting = Setting::first();
        $discountMsg = $discount->type == 'percentage' ? $setting->discount_msg : $setting->discount_rial_msg;
        $placeholders = ['%name%', '%type%' , '%code%' , '%amount%' , '%start_date%' , '%end_date%' , '%apply_on%'];
        $msg = str_replace($placeholders, [$discount->name, ($discount->type == 'percentage' ? 'درصدی' : 'ریالی'), $discount->code , $discount->percentage , \Morilog\Jalali\Jalalian::forge($discount->start_date)->format('Y/m/d'), \Morilog\Jalali\Jalalian::forge($discount->end_date)->format('Y/m/d') , $apply_on], $discountMsg);
        $users = User::all();
        foreach($users as $user){
            $message = new Message();
            $message->user_id = null;
            $message->receiver_id = $user->id;
            $message->message = $msg;
            $message->view = 0;
            $message->save();

            $lastMessage = new SupportLastMessage();
            $lastMessage->user_id = $user->id;
            $lastMessage->last_message = $msg;
            $lastMessage->last_message_datetime = $message->created_at;
            $lastMessage->save();
        }
    }
}
