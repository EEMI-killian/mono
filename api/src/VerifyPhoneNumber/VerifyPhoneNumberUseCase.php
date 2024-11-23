<?php 
namespace App\VerifyPhoneNumber;
use App\Gateways\Twilio\ITwilioGateway;


class VerifyPhoneNumberUseCase
{
    private ITwilioGateway $twilioGateway;
    function __construct(ITwilioGateway $twilioGateway) {
        $this->twilioGateway = $twilioGateway;
      }

    public function execute(array $data): string
    {
        try{
            do {
                $challenge = rand(100000, 999999);
            } while (in_array($challenge,         [123456, 654321, 111111, 222222, 333333, 444444, 555555, 666666, 777777, 888888, 999999]));
        
        $data['otpChallenge'] = $challenge;
        if($_ENV['PHONE_NUMBER_IS_FAKE']){
            $data['otpChallenge'] = 808080;
            return json_encode(["status" => true, "response" => "OTP sent successfully"]);
        }
        $this->twilioGateway->sendOtpSms($data);
        return json_encode(["status" => true, "response" => "OTP sent successfully"]);
        } catch (\Exception $e) {
            return json_encode(["status" => false, "error" => $e->getMessage()]);
        }
    }
}