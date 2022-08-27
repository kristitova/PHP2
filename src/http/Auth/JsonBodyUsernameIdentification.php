<?php

namespace GeekBrains\LevelTwo\http\Auth;

use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\AuthException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\User;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    public function user(Request $request): User
    {
        try {

            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {

            throw new AuthException($e->getMessage());
        }
        try {

            return $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {

            throw new AuthException($e->getMessage());
        }
    }
}
