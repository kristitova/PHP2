<?php

namespace Php\App\Blog\Commands;

use Php\App\Blog\Exceptions\ArgumentsException;
use Php\App\Blog\Exceptions\CommandException;
use Php\App\Blog\Exceptions\UserNotFoundException;
use Php\App\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Php\App\Blog\User;
use Php\App\Blog\UUID;

class CreateUserCommand
{
// Команда зависит от контракта репозитория пользователей,
// а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    /**
     * @throws ArgumentsException
     * @throws CommandException
     */
    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');

// Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
// Бросаем исключение, если пользователь уже существует
            throw new CommandException("User already exists: $username");
        }

// Сохраняем пользователя в репозиторий
        $this->usersRepository->save(new User(
            UUID::random(),
            $username,
            $arguments->get('first_name'), 
            $arguments->get('last_name')
        ));
    }

    private function userExists(string $username): bool
    {
        try {
// Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}