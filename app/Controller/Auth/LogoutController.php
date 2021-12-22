<?php

namespace Belajar\Controller\Auth;


use Belajar\App\View;
use Belajar\Config\Database;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\LogoutService;

class LogoutController {

    public LogoutService $logoutService;

    public function __construct()
    {
        $this->logoutService = new LogoutService();
    }

    public function logout()
    {
        $this->logoutService->destroy();
        View::redirect('/users/login');
    }


}