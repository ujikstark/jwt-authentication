<?php

namespace Belajar\Service\Auth;

use Belajar\Config\Database;
use Belajar\Repository\UserRepository;
use Exception;
use Firebase\JWT\JWT;

class CurrentLoginService {
    
    private UserRepository $userRepository;
    public $payload;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
    }

    // check the token
    public function validate() {

        $isExpired = false;
        
        if (isset($_COOKIE['access_token'])) {
            $accessToken = $_COOKIE['access_token'];
            
            // split access token jwt
            $tokenParts = explode('.', $accessToken);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signature = $tokenParts[2];
            
            // check the expiration time
            $accessTokenExp = json_decode($payload)->exp;
            
            if (($accessTokenExp - time()) < 0) {
                setcookie('access_token', '', 1, '/');
                $isExpired = true;
            }
            
            if (!$isExpired) {
                $user = $this->userRepository->findById(json_decode($payload)->user_id);

                if ($user != null) {
                     
                    if ($this->verify($header, $payload, $signature, $user->secretKey)) {

                        $newPayload = [
                            "user_id" => $user->id,
                            "role" => 'customer',
                            "exp" => time() + 60 * 2
                        ];
                        
                        return true;
                    } else {
                        return false;
                    }

                } else {
                    return false;
                }
                
            } 
            
        } else {
            $isExpired = true;
            
        }
        
        
        
        if (isset($_COOKIE['refresh_token']) && $isExpired) {

            $isExpired = false;
            // split refresh token jwt
            $refreshToken = $_COOKIE['refresh_token'];
            $tokenParts = explode('.', $refreshToken);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signature = $tokenParts[2];
            
            
            $refreshTokenExp = json_decode($payload)->exp;

            if (($refreshTokenExp - time()) < 0) {

                setcookie('refresh_token', '', 1, '/');
                $isExpired = true;

            }

            
            if (!$isExpired) {
                $user = $this->userRepository->findById(json_decode($payload)->user_id);
                
                if ($user != null) {
                    
                    
                    
                    if ($this->verify($header, $payload, $signature, $user->secretKey)) {

                        $newPayload = [
                            "user_id" => $user->id,
                            "role" => 'customer',
                            "exp" => time() + 60 * 2
                        ];
            
            
                        // JWT 
                        $accessToken = JWT::encode($newPayload, $user->secretKey, 'HS256');
                        setcookie('access_token', $accessToken, time() + 60, '/');

                        $this->payload = $newPayload;
                        return true;
                    } 
                } else {
                    return false;
                }   

                
                
            } 
            

       } else {
           throw new Exception("doesn't have access token, you need login");
       }
    }


    // verify jwt
    public function verify(string $header, string $payload, string $signature, string $secretKey) {

        // build a signature based on the header and payload using the secret

        $base64_url_header = JWT::urlsafeB64Encode($header);
        $base64_url_payload = JWT::urlsafeB64Encode($payload);
        $newSignature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secretKey, true);
        $base64_url_signature = JWT::urlsafeB64Encode($newSignature);

        if ($base64_url_signature == $signature) {
            return true;
        } else {
            return false;
        }
    }



}