<?php

namespace Belajar\Service;

use Belajar\Config\Database;
use Belajar\Domain\User;
use Belajar\Exception\ValidationException;
use Belajar\Model\User\UserLoginRequest;
use Belajar\Model\User\UserRegisterRequest;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\CurrentLoginService;
use Belajar\Service\Auth\LoginService;
use Belajar\Service\Auth\RegisterService;
use Exception;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

class CurrentLoginServiceTest extends TestCase {
    
    private UserRepository $userRepository;
    private RegisterService $registerService;
    private CurrentLoginService $currentLoginService;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->loginService = new LoginService($this->userRepository);
        $this->registerService = new RegisterService($this->userRepository);
        $this->currentLoginService = new CurrentLoginService();

        $this->userRepository->deleteAll();
    }

    public function testCurrentLoginSuccess() {

        $request = new UserRegisterRequest();
        $request->id = 1;
        $request->name = "ujik";
        $request->username = "ujik";
        $request->password = "rahasia";
        $request->secretKey = 'secret';
        $response = $this->registerService->register($request);
      
        $dateAccess = time() + 60;
        $dateRefresh = time() + 60 * 60;

        $accessTokenPayload = [
            "user_id" => $response->user->id,
            "role" => 'customer',
            "exp" => $dateAccess
        ];

        $refeshTokenPayload = [
            "user_id" => $response->user->id,
            "exp" => $dateRefresh
        ];  


        // JWT 
        $accessToken = JWT::encode($accessTokenPayload, $response->user->secretKey, 'HS256');
        $refreshToken = JWT::encode($refeshTokenPayload, $response->user->secretKey, 'HS256');

        
        
        $_COOKIE['access_token'] = $accessToken;
        $_COOKIE['refresh_token'] = $refreshToken;

        
        $this->assertTrue($this->currentLoginService->validate());
    }

    public function testCurrenLoginFailed() {

      $this->assertFalse($this->currentLoginService->validate());
      
        
    }





}