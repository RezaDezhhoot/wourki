<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;


class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting' , compact('setting'));
    }
    public function store(Request $request)
    {
        Setting::first()->update([
            'reagent_user_fee' => $request->reagent,
            'reagented_user_fee' => $request->reagented,
            // 'marketer_fee' => $request->marketer,
            'reagent_user_create_store' => $request->reagentCreateStore,
            // 'marketer_user_create_store' => $request->marketerCreateStore,
            'register_gift' => $request->register_gift,
            'first_buy_gift' => $request->first_buy_gift,
            'first_sell_gift' => $request->first_sell_gift,
            'welcome_msg' => $request->welcome_msg,
            'approve_store_msg' => $request->approve_store_msg,
            'reject_store_msg' => $request->reject_store_msg,
            'new_comment_msg' => $request->new_comment_msg,
            'checkout_msg' => $request->checkout_msg,
            'product_without_photo_msg' => $request->product_without_photo_msg,
            'finishing_subscription_plan_message' => $request->finishing_subscription_plan_message,
            'app_version' => $request->app_version,
            'ads_expire_days' => $request->ads_expire_days,
            'wallet_page_help_text' => $request->wallet_page_help_text,
            'support_page_help_text' => $request->support_page_help_text,
            'ads_page_help_text' => $request->ads_page_help_text,
            'chat_rules' => $request->chat_rules,
            'no_chat_message' => $request->no_chat_message,
            'no_messages' => $request->no_messages,
            'discount_msg' => $request->discount_msg,
            'discount_rial_msg' => $request->discount_rial_msg,
            'share_text' => $request->share_text
        ]);

        return back();
    }
}
