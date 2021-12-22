<?php

namespace Belajar\Controller;

use Belajar\Config\Database;
use Belajar\Domain\Session;
use Belajar\Domain\User;
use Belajar\Repository\SessionRepository;
use Belajar\Repository\UserRepository;
use Belajar\Service\SessionService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {   
        $connection = Database::getConnection();

        $this->homeController = new HomeController();
        $this->userRepository = new UserRepository($connection);
        $this->sessionRepository = new SessionRepository($connection);
        
        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest()
    {
        $this->homeController->index();
        $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();
        $this->expectOutputRegex("[Hello Eko]");
    }
}