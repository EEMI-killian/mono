<?php
namespace App\SignUp;


use App\Repository\User\IUserRepository;
use App\Entity\User;
use App\Enum\UserRole;

class SignUpUseCase {
    
    private IUserRepository $repository;
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }
    public function execute(array $data): string
    {
      try {
        if(!isset($data['firstName']) || strlen($data['firstName']) > 20 || strlen($data['firstName']) < 3 || strlen($data['firstName']) == null || preg_match('/[^a-zA-Z]/', $data['firstName'])){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['lastName']) || strlen($data['lastName']) > 30 || strlen($data['lastName'])  < 3 || strlen($data['lastName'])  == null || preg_match('/[^a-zA-Z]/', $data['lastName'])){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['email']) || $data['email'] == null || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['password']) || $data['password'] == null || strlen($data['password']) < 12){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['phoneNumber']) || $data['phoneNumber'] == null || !preg_match('/^[0-9]{10}+$/', $data['phoneNumber'])){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['address']) || $data['address'] == null || strlen($data['address']) < 10){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['city']) || $data['city'] == null || strlen($data['city']) < 3){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['state']) || $data['state'] == null || strlen($data['state']) < 3){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['zipCode']) || $data['zipCode'] == null || !preg_match('/^[0-9]{5}+$/', $data['zipCode'])){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['country']) || $data['country'] == null || strlen($data['country']) < 3){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if(!isset($data['birthDate']) || $data['birthDate'] == null || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}+$/', $data['birthDate'])){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if($this->repository->findByEmail($data['email']) !== null){
            return json_encode(["status" => false, "error" => "User already exists"]);
        }    
        $user = new User();
        $user->setAddress($data['address']);
        $user->setBirthDate($data['birthDate']);
        $user->setCity($data['city']);
        $user->setCountry($data['country']);
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $salt = bin2hex(random_bytes(16));
        $password = hash('sha256', $data['password'] . $salt);
        $user->setPassword($password);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setState($data['state']);
        $user->setZipCode($data['zipCode']);
        $user->setPhoneNumberVerified(false);
        $user->setEmailVerified(false);
        $user->setrole(UserRole::USER);
        $this->repository->save($user );
      return json_encode(["status" => true, "message" => "User created successfully"]);
      } catch (\Exception $e) {
        return json_encode(["status" => false, "error" => $e->getMessage()]);
      }
    }

  }