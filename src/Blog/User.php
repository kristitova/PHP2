<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;


class User
{

    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $hashedPassword,
        private Name $name
    ) {
    }

    public function username(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        $firstName = $this->name()->first();
        $lastName = $this->name()->last();
        return "Пользователь $firstName $lastName" . PHP_EOL;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }


    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }


    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    public static function createFrom(
        string $username,
        string $password,
        Name $name
    ): self {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $username,
            self::hash($password, $uuid),
            $name
        );
    }
}
