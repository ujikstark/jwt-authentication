<?php

namespace Belajar\Service;

use Belajar\Config\Database;
use Belajar\Domain\User;
use Belajar\Exception\ValidationException;
use Belajar\Model\UserLoginRequest;
use Belajar\Model\UserPasswordUpdateRequest;
use Belajar\Model\UserProfileUpdateRequest;
use Belajar\Model\UserRegisterRequest;
use Belajar\Repository\SessionRepository;
use Belajar\Repository\UserRepository;
use PHPUnit\Framework\TestCase;


class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

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
        $response = $this->userService->register($request);

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

        $this->userService->register($request);


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

        $this->userService->register($request);
    }

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->username = 'ujika';
        $request->password = "tes";

        $this->userService->login($request);
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
        
        $this->userService->register($request);
      

        $request = new UserLoginRequest();
        $request->username = "ujik";
        $request->password = 'rahasi';

        
        $this->userService->login($request);
        

        
    }

    public function testLoginSuccess() 
    {
        $request = new UserRegisterRequest();
        $request->id = 1;
        $request->name = "ujik";
        $request->username = "ujik";
        $request->password = "rahasia";
        $request->secretKey = 'secret';
        $response = $this->userService->register($request);
      

        $request = new UserLoginRequest();
        $request->username = "ujik";
        $request->password = 'rahasia';

        
        $result = $this->userService->login($request);
        
        $this->assertEquals($response->user->username, $result->user->username);
        $this->assertEquals($response->user->password, $result->user->password);
    }

    // public function testUpdateSuccess()
    // {
    //     $user = new User();
    //     $user->id = "eko";
    //     $user->name = "Eko";
    //     $user->password = password_hash("eko", PASSWORD_BCRYPT);
    //     $this->userRepository->save($user);

    //     $request = new UserProfileUpdateRequest();
    //     $request->id = "eko";
    //     $request->name = "Budi";

    //     $this->userService->updateProfile($request);

    //     $result = $this->userRepository->findById($user->id);
        
    //     self::assertEquals($result->name, $request->name);
    // }

    // public function testUpdateValidationError()
    // {   
    //     $this->expectException(ValidationException::class);
    //     $request = new UserProfileUpdateRequest();
    //     $request->id = "";
    //     $request->name = "";

    //     $this->userService->updateProfile($request);
    // }

    // public function testUpdateNotFound()
    // {
    //     $this->expectException(ValidationException::class);

    //     $request = new UserProfileUpdateRequest();
    //     $request->id = "eko";
    //     $request->name = "Budi";

    //     $this->userService->updateProfile($request);
    // }

    // public function testUpdatePasswordSuccess()
    // {
    //     $user = new User();
    //     $user->id = "eko";
    //     $user->name = "Eko";
    //     $user->password = password_hash("eko", PASSWORD_BCRYPT);
    //     $this->userRepository->save($user);

    //     $request = new UserPasswordUpdateRequest();
    //     $request->id = "eko";
    //     $request->oldPassword = "eko";
    //     $request->newPassword = "new";

    //     $this->userService->updatePassword($request);

    //     $result = $this->userRepository->findById($user->id);
    //     self::assertTrue(password_verify($request->newPassword, $result->password));



    // }

    // public function testUpdatePasswordValidationError()
    // {
    //     $this->expectException(ValidationException::class);

    //     $request = new UserPasswordUpdateRequest();
    //     $request->id = "eko";
    //     $request->oldPassword = "";
    //     $request->newPassword = "";

    //     $this->userService->updatePassword($request);
    // }

    // public function testUpdatePasswordWrongOldPassword()
    // {
    //     $this->expectException(ValidationException::class);
    //     $user = new User();
    //     $user->id = "eko";
    //     $user->name = "Eko";
    //     $user->password = password_hash("eko", PASSWORD_BCRYPT);
    //     $this->userRepository->save($user);

    //     $request = new UserPasswordUpdateRequest();
    //     $request->id = "eko";
    //     $request->oldPassword = "salah";
    //     $request->newPassword = "new";

    //     $this->userService->updatePassword($request);

    // }

    // public function testUpdatePasswordNotFound()
    // {   
    //     $this->expectException(ValidationException::class);

    //     $request = new UserPasswordUpdateRequest();
    //     $request->id = "eko";
    //     $request->oldPassword = "eko";
    //     $request->newPassword = "new";

    //     $this->userService->updatePassword($request);
    // }   
}