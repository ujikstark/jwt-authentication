<?php

namespace Belajar\Service;

use Belajar\Config\Database;
use Belajar\Exception\ValidationException;
use Belajar\Model\User\UserLoginRequest;
use Belajar\Model\User\UserRegisterRequest;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\LoginService;
use Belajar\Service\Auth\RegisterService;
use PHPUnit\Framework\TestCase;

class LoginServiceTest extends TestCase {
    
    private UserRepository $userRepository;
    private LoginService $loginService;
    private RegisterService $registerService;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->loginService = new LoginService($this->userRepository);
        $this->registerService = new RegisterService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->username = 'ujika';
        $request->password = "tes";

        $this->loginService->login($request);
    }


    public function testLoginWrongPassword()
    {

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = 1;
        $request->name = "ujik";
        $request->username = "ujik";
        $request->password = "rahasia";
        $request->secretKey = 'secret';
        
        $this->registerService->register($request);
      

        $request = new UserLoginRequest();
        $request->username = "ujik";
        $request->password = 'rahasi';

        
        $this->loginService->login($request);
        

        
    }

    public function testLoginSuccess() 
    {
        $request = new UserRegisterRequest();
        $request->id = 1;
        $request->name = "ujik";
        $request->username = "ujik";
        $request->password = "rahasia";
        $request->secretKey = 'secret';
        $response = $this->registerService->register($request);
      

        $request = new UserLoginRequest();
        $request->username = "ujik";
        $request->password = 'rahasia';

        
        $result = $this->loginService->login($request);
        
        $this->assertEquals($response->user->username, $result->user->username);
        $this->assertEquals($response->user->password, $result->user->password);
    }

}