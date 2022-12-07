<?php

namespace App\Listeners;

use App\Events\UserWriteComment;
use App\Message;
use App\Setting;
use App\SupportLastMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentMessageToStoreOwner
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
     * @param  UserWriteComment  $event
     * @return void
     */
    public function handle(UserWriteComment $event)
    {
        $product = $event->productSeller;
        $commenter = $event->user;

        $storeOwner = $product->store->user;

        $msg = Setting::first()->new_comment_msg;
        $msg = str_replace(['%full_name%'] , [$storeOwner->first_name . ' ' . $storeOwner->last_name] , $msg);

        $message = new Message();
        $message->user_id = null;
        $message->receiver_id = $storeOwner->id;
        $message->message = $msg;
        $message->view = 0;
        $message->save();

        $lastMessage = new SupportLastMessage();
        $lastMessage->user_id = $storeOwner->id;
        $lastMessage->last_message = $msg;
        $lastMessage->last_message_datetime = $message->created_at;
        $lastMessage->save();
    }
}
