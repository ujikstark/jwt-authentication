<?php

namespace Belajar\Controller\Auth;

use Belajar\App\View;
use Belajar\Config\Database;
use Belajar\Model\User\UserLoginRequest;
use Belajar\Repository\UserRepository;
use Belajar\Service\Auth\LoginService;
use Exception;
use Firebase\JWT\JWT;


class LoginController {

    public UserRepository $userRepository;
    public LoginService $loginService;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->loginService = new LoginService($this->userRepository);
    }

    public function login() {
       
        View::render('User/login', [
            "title" => "Login",
        ]);
            
    }

    public function postLogin() 
    {
        $request = new UserLoginRequest();
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];
        
        try {
            $response = $this->loginService->login($request);
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

            // more save 
            // setcookie('access_token', $jwt, time()+60*60*24*30 , '/', "", true, true);
            
            
            setcookie('access_token', $accessToken, $dateAccess, '/');
            setcookie('refresh_token', $refreshToken, $dateRefresh , '/');

            View::redirect('/');
        } catch (Exception $exception) {
            View::render('User/login', [
                'title' => 'Login user',
                'error' => $exception->getMessage()
            ]);
        }
    }

}