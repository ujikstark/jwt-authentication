<?php

namespace Belajar\Service\Auth;


class LogoutService {


    public function destroy()
    {
     
        setcookie('access_token', '', 1, "/");
        setcookie('refresh_token', '', 1, "/");

    }

   
}

