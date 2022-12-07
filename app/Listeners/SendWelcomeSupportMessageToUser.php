<?php

namespace App\Listeners;

use App\Events\UserVerifyMobile;
use App\Message;
use App\Setting;
use App\SupportLastMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeSupportMessageToUser
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
     * @param  UserVerifyMobile  $event
     * @return void
     */
    public function handle(UserVerifyMobile $event)
    {
        $message = new Message();
        $message->user_id = null;
        $message->receiver_id = $event->user->id;
        $giftPrice = Setting::first()->register_gift;
        $welcomeMsg = Setting::first()->welcome_msg;
        $placeholders = ['%full_name%' , '%gift_price%'];
        $msg = str_replace($placeholders , [$event->user->first_name . ' ' . $event->user->last_name , $giftPrice] ,$welcomeMsg );
        $message->message = $msg;
        $message->view = 0;
        $message->save();

        $lastMessage = new SupportLastMessage();
        $lastMessage->user_id = $event->user->id;
        $lastMessage->last_message = $msg;
        $lastMessage->last_message_datetime = $message->created_at;
        $lastMessage->save();

    }
}
