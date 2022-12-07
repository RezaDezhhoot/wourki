<?php

namespace App\Listeners;

use App\SMS;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\PurchaseProducts\Facade\SMSApi;
use GuzzleHttp\Client;
use Log;
use Throwable;

class SMSHandlerListener
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


    public function handle($event)
    {
        if ($event->message == null) {
            switch ($event->type) {
                case 'bill':
                    foreach($event->receptors as $receptor){
                    $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $receptor . "&token=کاربر" . "&template=newOrder";
                    $client = new Client();
                    try{
                    $client->get($url);
                    }catch (Throwable $e){
                        Log::info($e->getMessage());
                    }
                    }
            }
        }

        // $SMSApi = new SMSApi($event->message, $event->receptors);
        // $result = $SMSApi->send();

        // if (!$result instanceof \Exception) {

        //     foreach ($result as $record) {

        //         $record = (array)$record;

        //         \DB::transaction(function () use ($result, $event, $record) {

        //             SMS::create([
        //                 'type' => $event->type,
        //                 'message_id' => $record['messageid'],
        //                 'message' => $record['message'],
        //                 'status' => $record['status'],
        //                 'status_text' => $record['statustext'],
        //                 'sender' => $record['sender'],
        //                 'receptor' => $record['receptor'],
        //                 'date' => $record['date'],
        //                 'cost' => $record['cost'],
        //             ]);

        //         });
        //     }

        // };

    }
}
