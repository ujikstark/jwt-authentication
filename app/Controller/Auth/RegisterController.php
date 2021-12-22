<?php

namespace Belajar\Controller\Auth;

use Belajar\App\View;
use Belajar\Config\Database;
use Belajar\Exception\ValidationException;
use Belajar\Model\User\UserRegisterRequest;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\RegisterService;
use DateTime;

class RegisterController {
    
    public UserRepository $userRepository;
    public RegisterService $registerService;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->registerService = new RegisterService($this->userRepository);
    }

    public function register() {
        View::render('User/register', [
            'title' => 'Register new user'
        ]);
    }

    public function postRegister() {

        $request = new UserRegisterRequest();
        $request->name = $_POST['name'];
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];


        $date = new DateTime();

        $secretKey = 'fsjdhfjsdkh#@j^dfffk' .  $date->format('Y-m-d H:i:s.u') . 'we#fds@^12fiqwe@112ffe';
        
        $request->secretKey = $secretKey;

        try {
            $this->registerService->register($request);
            // redirect to login
            View::redirect('/users/login');
        } catch (ValidationException $exception) {
            View::render('User/register', [
                'title' => 'Register new user',
                'error' => $exception->getMessage()
            ]);
        }
      
    }
}