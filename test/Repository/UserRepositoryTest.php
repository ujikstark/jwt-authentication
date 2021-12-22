<?php

namespace Belajar\Repository;

use PHPUnit\Framework\TestCase;
use Belajar\Config\Database;
use Belajar\Domain\User;
use DateTime;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection('prod'));
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = 1;
        $user->name = "ujik";
        $user->username = "ujik";
        $user->password = "rahasia";

        $date = new DateTime();

        $user->secretKey = 'fsjdhfjsdkh#@j^dfffk' .  $date->format('Y-m-d H:i:s.u') . 'we#fds@^12fiqwe@112ffe';

        $this->userRepository->save($user);
        

        $result = $this->userRepository->findById($user->id);
        
        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $user->password);

    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById("notfound");

        self::assertNull($user);
    }

    public function testUpdate()
    {
        $user = new User();
        $user->id = 1;
        $user->name = "ujik";
        $user->username = "ujik";
        $user->password = "rahasia";
        $user->secretKey = 'tes';

        $this->userRepository->save($user);

        $user->name = "Budi";
        $result = $this->userRepository->update($user);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $user->password);
    }

    
}