<?php
namespace App\Gateways\Twilio;

use Twilio\Rest\Client;


class TwilioGateway implements ITwilioGateway {

    private string $TWILIO_SID;
    private string $TWILIO_AUTH_TOKEN;
    private string $TWILIO_SERVICE_SID;

    public function __construct(string $TWILIO_SID, string $TWILIO_AUTH_TOKEN, string $TWILIO_SERVICE_SID)
    {
        $this->TWILIO_SID = $TWILIO_SID;
        $this->TWILIO_AUTH_TOKEN = $TWILIO_AUTH_TOKEN;
        $this->TWILIO_SERVICE_SID = $TWILIO_SERVICE_SID;
    }

    public function sendOtpSms(array $data): void
    {
        $twilio = new Client($this->TWILIO_SID, $this->TWILIO_AUTH_TOKEN);
        $verification = $twilio->verify->v2->services($this->TWILIO_SERVICE_SID)
                                   ->verifications
                                   ->create($data['phoneNumber'], "sms");

        print($verification->sid);
    }


}