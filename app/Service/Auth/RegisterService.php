<?php

namespace Belajar\Service\Auth;

use Belajar\Config\Database;
use Belajar\Domain\User;
use Belajar\Exception\ValidationException;
use Belajar\Model\User\UserRegisterRequest;
use Belajar\Model\User\UserRegisterResponse;
use Belajar\Repository\UserRepository;

class RegisterService {

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);
    
        try {
            Database::beginTransaction();
            
            
            $user = $this->userRepository->findById($request->id);

            if ($user != null){
                throw new ValidationException("User already exists");
            }
    
            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $user->secretKey = $request->secretKey;

            $this->userRepository->save($user);
            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commitTransaction();
    
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if ($request->name == null || $request->username == null || $request->password == null ||
            trim($request->name) == "" || trim($request->username) == "" || trim($request->password) == "")
        {
            throw new ValidationException("Full Name, Username, Password, Re-enter Password can not blank");
        }
    }
}