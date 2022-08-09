<?php

namespace Php\App\Blog\Repositories\UsersRepository;

use Php\App\Blog\Exceptions\InvalidArgumentException;
use Php\App\Blog\Exceptions\UserNotFoundException;
use Php\App\Blog\User;
use Php\App\Blog\UUID;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
// Бросаем исключение, если пользователь не найден
        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot get user: $uuid"
            );
        }

        return $this->getUser($statement, $uuid);
    }


    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (first_name, last_name, uuid, username)
                VALUES (:first_name, :last_name, :uuid, :username)'
        );

       
        var_dump($statement);
// Выполняем запрос с конкретными значениями
        $statement->execute([
            ':first_name' => $user->first(),
            ':last_name' => $user->last(),
            // Это работает, потому что класс UUID
// имеет магический метод __toString(),
// который вызывается, когда объект
// приводится к строке с помощью (string)
            ':uuid' => (string)$user->uuid(),
            ':username' => $user->username()
        ]);
    }


    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    private function getUser(\PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }
// Создаём объект пользователя с полем username
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );
    }
}