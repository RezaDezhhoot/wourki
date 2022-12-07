<?php

namespace App\Listeners;

use App\Events\CheckoutStore;
use App\Message;
use App\Setting;
use App\SupportLastMessage;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCheckoutMessageToStoreOwner
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
     * @param  CheckoutStore  $event
     * @return void
     */
    public function handle(CheckoutStore $event)
    {
        if ($event->data instanceof User) {
            $owner = $event->data;
        } else {
            //instance of store
            $owner = $event->data->user;
        }

        $msg = Setting::first()->checkout_msg;
        $msg = str_replace(['%full_name%'], [$owner->first_name . ' ' . $owner->last_name], $msg);

        $message = new Message();
        $message->user_id = null;
        $message->receiver_id = $owner->id;
        $message->message = $msg;
        $message->view = 0;
        $message->save();

        $lastMessage = new SupportLastMessage();
        $lastMessage->user_id = $owner->id;
        $lastMessage->last_message = $msg;
        $lastMessage->last_message_datetime = $message->created_at;
        $lastMessage->save();

    }
}
