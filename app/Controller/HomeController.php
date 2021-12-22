<?php

namespace Belajar\Controller;

use Belajar\App\View;
use Belajar\Config\Database;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\CurrentLoginService;

class HomeController
{

    public UserRepository $userRespository;
    public CurrentLoginService $currentLogin;

    public function __construct()
    {
        $this->userRespository = new UserRepository(Database::getConnection());
        $this->currentLogin = new CurrentLoginService();
    }

    public function index() {

        $this->currentLogin->validate();
        
        $payload = $this->currentLogin->payload;
        
        if ($payload!= null) {
           
            $userId = $payload['user_id'];
            
            $user = $this->userRespository->findById($userId);

            View::render('/Home/index', [
                "title" => "Dashboard",
                "user" => [
                    "name" => $user->name ?? ''
                ]
            ]);


        } else {
            header('refresh: 0');
            View::render('Home/index', [
                "title" => "Dashboard"
            ]);
        }

        
            
    }
}

