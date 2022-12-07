<?php

namespace App\Listeners;

use App\Events\ApproveStore;
use App\Message;
use App\Setting;
use App\SupportLastMessage;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Throwable;

class SendApproveStoreMessageToUser
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
     * @param  ApproveStore  $event
     * @return void
     */
    public function handle(ApproveStore $event)
    {
        
        $msg = Setting::first()->approve_store_msg;
        $user_name = $event->store->user->first_name . ' ' . $event->store->user->last_name;
        $msg = str_replace(['%full_name%' , '%store_name%'] , [$user_name , $event->store->name] , $msg);
        $token = url()->to('store') . '/' . $event->store->user_name;
        try{
        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $event->store->user->mobile . "&token='.$token.'&template=storeApproved";
        $client = new Client();
        $client->get($url);
        }
        catch(Throwable $e){
            Log::info($e->getMessage());
        }
        $message = new Message();
        $message->user_id = null;
        $message->receiver_id = $event->store->user->id;
        $message->message = $msg;
        $message->view = 0;
        $message->save();
        $lastMessage = new SupportLastMessage();
        $lastMessage->user_id = $event->store->user->id;
        $lastMessage->last_message = $msg;
        $lastMessage->last_message_datetime = $message->created_at;
        $lastMessage->save();
    }
}
