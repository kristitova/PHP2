<?php

namespace Php\App\Blog\Repositories\UsersRepository;

use Php\App\Blog\Exceptions\UserNotFoundException;
use Php\App\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Php\App\Blog\User;
use Php\App\Blog\UUID;

class DummyUsersRepository implements UsersRepositoryInterface
{

    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }

    public function get(UUID $uuid): User
    {
        throw new UserNotFoundException("Not found");
    }

    public function getByUsername(string $username): User
    {
        return new User(UUID::random(), "user123", "first", "last");
    }
}
