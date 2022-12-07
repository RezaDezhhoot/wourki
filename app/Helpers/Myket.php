<?php

namespace App\Helpers;


use Log;
use Throwable;

class Myket
{

    protected $guzzle;
    protected $PACKAGE_NAME;
    protected $TOKEN;
    protected $data;
    public function __construct()
    {
        $this->data = [];
        $this->PACKAGE_NAME = env('MYKET_PACKAGE_NAME');
        $this->TOKEN = env('MYKET_TOKEN');
        $this->guzzle = new \GuzzleHttp\Client(["base_uri" => "https://developer.myket.ir/api/"]);
    }
    public function verifyPurchase($token , $sku){
        try{
        $response = $this->guzzle->get("applications/$this->PACKAGE_NAME/purchases/products/$sku/tokens/$token" , [
            'headers' => ['X-Access-Token' => $this->TOKEN]
        ]);
        $data = json_decode($response->getBody()->getContents());
        if($data->purchaseState == 0){
            try{
            $this->data = json_decode($data->developerPayload);
            }
            catch(Throwable $e){
                Log::info('Exception in myket buying :' . $e->getMessage());
            }
            return true;
        } 
        return false;
    }
    catch(Throwable $e){
        Log::info('Exception in request to myket' , $e->getMessage());
        return false;
    }
    }
    public function getPayload(){
        return $this->data;
    }

    
}
