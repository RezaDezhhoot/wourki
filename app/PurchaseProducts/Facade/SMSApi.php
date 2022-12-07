<?php

namespace App\PurchaseProducts\Facade;

use Exception;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\KavenegarApi as Kavenegar;
use Log;

class SMSApi
{

    private $apiKey;
    private $sender;
    private $message;
    private $receptor;
    protected $API;


    public function __construct($message, array $receptor, $sender = null)
    {
        $this->apiKey = config('SMS.Kavenegar.key');
        $this->sender = config('SMS.Kavenegar.sender');
        $this->message = $message;
        $this->receptor = $receptor;
        $this->API = new Kavenegar($this->apiKey);
    }

    public function verification($receptor, $token)
    {
        try {
            $token2 = "";
            $token3 = "";
            $template = "codeTest";
            $type = "sms";//sms | call

            return $this->API->VerifyLookup($receptor, $token, $token2, $token3, $template, $type);

        } catch (Exception $e) {
            return false;
        }
    }

    public function StatusLocalMessageId()
    {
        try {
            $localIds = array("{ LocalId #1 } ", "{ LocalId #2 }");
            $result = $this->API->StatusLocalMessageId($localIds);
            if ($result) {
                var_dump($result);
            }
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
        }
    }

    public function SendByPostalCode()
    {
        try {
            $postalcode = "441585";
            $sender = "";
            $message = "خدمات پیام کوتاه کاوه نگار";
            $mcistartindex = "-1";
            $mcicount = "10";
            $mtnstartindex = "-1";
            $mtncount = "10";
            $date = (new DateTime('2015-07-30'))->getTimestamp();
            $result = $this->API->SendbyPostalCode($sender, $postalcode, $message, $mcistartindex, $mcicount, $mtnstartindex, $mtncount, $date = null);
            if ($result) {
                var_dump($result);
            }
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
        }
    }

    public function SendArray()
    {
        try {
            $senders = array("{ Sender Line #1}", "{ Sender Line #2}");
            $receptors = array("{ Receptor #1 }", "{ Receptor #2 }");
            $messages = array("{ Messages #1 }", "{ Messages #2 }");
            $result = $this->API->SendArray($senders, $receptors, $messages);
            if ($result) {
                var_dump($result);
            }
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
        }
    }

    public function Send()
    {
        try {
            return $this->API->Send($this->sender, $this->receptor, $this->message);
        } catch (Exception $e) {
            Log::info('message not sent');
            Log::info($e->getMessage());
            return $e;
        }
    }

    public function SendOutbox()
    {
        try {
            $startdate = (new DateTime('2015-07-1'))->getTimestamp();
            $enddate = (new DateTime('2015-07-30'))->getTimestamp();
            $sender = "{ Sender Line }";
            $result = $this->API->SelectOutbox($startdate, $enddate, $sender);
            if ($result) {
                var_dump($result);
            }
        } catch (ApiException $e) {
            echo $e->errorMessage();
        } catch (HttpException $e) {
            echo $e->errorMessage();
        }
    }

}
