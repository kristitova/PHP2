<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Exceptions\CommandException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    // Команда зависит от контракта репозитория пользователей,
    // а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws ArgumentsException
     * @throws CommandException
     */
    public function handle(Arguments $arguments): void
    {

        $this->logger->info("Create user command started");

        $username = $arguments->get('username');

        // Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {

            $this->logger->warning("User already exists: $username");
            throw new CommandException("User already exists: $username");
        }

        $uuid = UUID::random();

        $this->usersRepository->save(new User(
            $uuid,
            $username,
            new Name($arguments->get('first_name'), $arguments->get('last_name'))
        ));

        $this->logger->info("User created: $uuid");
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
