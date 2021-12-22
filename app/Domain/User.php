<?php

namespace Belajar\Domain;

class User
{
    public ?string $id = NULL;
    public string $name;
    public string $username;
    public string $password;
    public string $secretKey;
}