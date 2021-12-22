<?php

namespace Belajar\Controller;

use Belajar\App\View;
use Belajar\Config\Database;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\CurrentLoginService;
use Exception;

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

        try {
            $this->currentLogin->validate();
        
            $payload = $this->currentLogin->payload;
            
            if ($payload != null) {
                // header('refresh: 0');
            
                $userId = $payload['user_id'];
                
                $user = $this->userRespository->findById($userId);

                View::render('/Home/index', [
                    "title" => "Dashboard",
                    "user" => [
                        "name" => $user->name ?? ''
                    ]
                ]);


            } else {
                View::render('Home/index', [
                    "title" => "Login"
                ]);
            }
        } catch (Exception $ex) {
            View::render('Home/index', [
                "title" => "Login",
                'error' => $ex->getMessage()
            ]);
        }

        

        
            
    }
}

