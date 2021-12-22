<?php

namespace Belajar\Service;

use Belajar\Config\Database;
use Belajar\Domain\User;
use Belajar\Exception\ValidationException;
use Belajar\Model\User\UserRegisterRequest;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\LoginService;
use Belajar\Service\Auth\RegisterService;
use PHPUnit\Framework\TestCase;

class RegisterServiceTest extends TestCase {
    
    private UserRepository $userRepository;
    private RegisterService $registerService;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->loginService = new LoginService($this->userRepository);
        $this->registerService = new RegisterService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess()
    {   
        $request = new UserRegisterRequest();
        $request->id = 1;
        $request->name = "Ujik";
        $request->username = "ujik";
        $request->password = "rahasia";
        $request->secretKey = 'secret';
        $response = $this->registerService->register($request);

        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->name, $response->user->name);
        self::assertNotEquals($request->password, $response->user->password);
        self::assertTrue(password_verify($request->password, $response->user->password));
        self::assertEquals($request->secretKey, $response->user->secretKey);
    }

    public function testRegisterFailed()
    {
        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";

        $this->registerService->register($request);


    }

    public function testRegisterDuplicate()
    {
        $user = new User();
        $user->id = 1;
        $user->name = "ujik";
        $user->username = "ujik";
        $user->password = "tes";
        $user->secretKey = "secret";

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = 1;
        $request->name = "ujik";
        $request->username = "ujik";
        $request->password = "tes";
        $request->secretKey = "rahasia";

        $this->registerService->register($request);
    }


}