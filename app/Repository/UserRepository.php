<?php

namespace Belajar\Repository;

use Belajar\Domain\User;

class UserRepository
{

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User {

        $statement = $this->connection->prepare("INSERT INTO users(id, name, username, password, secret_key) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([
            $user->id, $user->name, $user->username, $user->password, $user->secretKey
        ]);

        return $user;
    }


    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET name = ? WHERE id = ?");
        $statement->execute([
            $user->name,
            $user->id
        ]);

        return $user;
    }

    public function findById(?string $id): ?User {

        if ($id != null) {
            $statement = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
            $statement->execute([$id]);

            try {
                if ($row = $statement->fetch()) {
                    $user = new User();
                    $user->id = $row['id'];
                    $user->name = $row['name'];
                    $user->username = $row['username'];
                    $user->password = $row['password'];
                    $user->secretKey = $row['secret_key'];
        
                    return $user;
                } else {
                    return null;
                }
            } finally {
                $statement->closeCursor();
            }
        } else {
            return null;
        }
        
        
    }

    public function findByUsername(?string $username): ?User {

        if ($username != null) {
            $statement = $this->connection->prepare("SELECT * FROM users WHERE username = ?");
            $statement->execute([$username]);

            try {
                if ($row = $statement->fetch()) {
                    $user = new User();
                    $user->id = $row['id'];
                    $user->name = $row['name'];
                    $user->username = $row['username'];
                    $user->password = $row['password'];
                    $user->secretKey = $row['secret_key'];
        
                    return $user;
                } else {
                    return null;
                }
            } finally {
                $statement->closeCursor();
            }
        } else {
            return null;
        }
        
        
    }

    public function findByToken(?string $token): ?string {

        if ($token != null) {
            $statement = $this->connection->prepare("SELECT * FROM refresh_tokens WHERE refresh_token = ?");
            $statement->execute([$token]);

            try {
                if ($row = $statement->fetch()) {
                    
                    return $row['user_id'];
                } else {
                    return null;
                }
            } finally {
                $statement->closeCursor();
            }
        } else {
            return null;
        }
        
        
    }

    public function deleteAll(): void {
        $this->connection->exec("DELETE from users");
    }
}