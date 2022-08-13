<?php

namespace Php\App\Blog\Repositories\UsersRepository;

use Php\App\Blog\User;
use Php\App\Blog\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}