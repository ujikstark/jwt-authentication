<?php

namespace Belajar\Service\Auth;

use Belajar\Exception\ValidationException;
use Belajar\Model\User\UserLoginRequest;
use Belajar\Model\User\UserLoginResponse;
use Belajar\Repository\UserRepository;

class LoginService {

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findByUsername($request->username);

        if ($user == null) {
            throw new ValidationException("Username or password is wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;

            return $response;
        } else {
            throw new ValidationException("Username or password is wrong");
        }
    }

    private function validateUserLoginRequest(UserLoginRequest $request) {
        if ($request->username == null || $request->password == null ||
            trim($request->username) == "" || trim($request->password) == "")
        {
            throw new ValidationException("Username, Password can not blank");
        }
    }
}