<?php

namespace App\Listeners;

use App\Events\RejectStore;
use App\Message;
use App\Setting;
use App\SupportLastMessage;
use GuzzleHttp\Client;
use Log;
use Throwable;

class SendRejectStoreMessageToUser
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
     * @param  RejectStore  $event
     * @return void
     */
    public function handle(RejectStore $event)
    {
        
        $msg = Setting::first()->reject_store_msg;
        if(is_null($msg)){
            $msg = '';
        }
        else{
        $user_name = $event->store->user->first_name . ' ' . $event->store->user->last_name;
        $msg = str_replace(['%full_name%', '%store_name%'], [$user_name, $event->store->name], $msg);
        }
        if($event->customMessage){
            $msg .= ' '.$event->customMessage;
        }
        // try {
        //     $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $event->store->user->mobile . "&token=کاربر&template=newMessage";
        //     $client = new Client();
        //     $client->get($url);
        // } catch (Throwable $e) {
        //     Log::info($e->getMessage());
        // }
        $message = new Message();
        $message->user_id = null;
        $message->receiver_id = $event->store->user_id;
        $message->message = $msg;
        $message->view = 0;
        $message->save();

        $lastMessage = new SupportLastMessage();
        $lastMessage->user_id = $event->store->user_id;
        $lastMessage->last_message = $msg;
        $lastMessage->last_message_datetime = $message->created_at;
        $lastMessage->save();
    }
}
