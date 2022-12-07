<?php

namespace App\Helpers;


class Pushe
{
    private $token;
    private $id;
   public function __construct()
   {
        $this->token = '1cb13d0533bdbb32d4f4c5ccf0b62190753a1e63';
        $this->id = '5dn6yor9j187qnke';
   }
   public function sendNotification($title , $notification , $filters){
            
        $ch = curl_init('https://api.pushe.co/v2/messaging/notifications/');

        curl_setopt_array($ch, array(
            CURLOPT_POST  => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Token " . $this->token,
            ),
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'app_ids' => $this->id,
            'data' => array(
                'title' => $title,
                'content' => $notification
            ),
            'filters' => $filters
        )));

        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
   }
}
