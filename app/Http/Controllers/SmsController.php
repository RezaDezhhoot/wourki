<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class SmsController extends Controller
{
    public function send()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $sms_client = new SoapClient('http://payamak-service.ir/SendService.svc?wsdl', array('encoding' => 'UTF-8'));

        try {
            $parameters['userName'] = "c.rmoazeni";
//            $parameters['password'] = "91883";
            $parameters['fromNumber'] = "SimCard";
            $parameters['fromNumber'] = "3232";
            $parameters['toNumbers'] = array("09134089821");
            $parameters['messageContent'] = "message content";
            $parameters['isFlash'] = false;
            $recId = array();
            $status = array();
            $parameters['recId'] = &$recId;
            $parameters['status'] = &$status;

            $sms_client->SendBatchSms($parameters)->SendBatchSmsResult;
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }


        /*ini_set("soap.wsdl_cache_enabled", "0");
        $sms_client = new SoapClient('http://payamak-service.ir/SendService.svc?wsdl', array('encoding'=>'UTF-8'));

        $parameters['userName'] = "c.rmoazeni";
        $parameters['password'] = "91883";

        echo $sms_client->GetCredit($parameters)->GetCreditResult;*/
    }
}
