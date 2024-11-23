<?php 
namespace App\Gateways\Twilio;

interface ITwilioGateway
{
    public function sendOtpSms(array $data): void;
}