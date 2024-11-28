<?php 

namespace App\CheckEmail;


use App\Repository\User\IUserRepository;


class CheckEmailUseCase {
    
    private IUserRepository $repository;
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }
    public function execute(array $data): string
    {
        if(!isset($data['email']) || $data['email'] == null || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            return json_encode(["status" => false, "error" => "Invalid input data "]);
        }
        if($this->repository->findByEmail($data['email']) !== null){
            return json_encode(["status" => false, "error" => "User already exists"]);
        }    
        return json_encode(["status" => true]);
    }
}