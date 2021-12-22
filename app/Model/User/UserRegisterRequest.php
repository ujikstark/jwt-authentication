<?php

namespace Belajar\Model\User;

class UserRegisterRequest
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $username = null;
    public ?string $password = null;
    public ?string $secretKey = null;
    
}