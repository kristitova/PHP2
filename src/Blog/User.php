<?php

namespace Php\App\Blog;


use Php\App\Blog\UUID;

class User
{

    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $firstName,
        private string $lastName

    )
    {
    }

    public function username(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        $firstName = $this->first();
        $lastName = $this->last();
        return "Пользователь $firstName $lastName" . PHP_EOL;
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

 /**
     * @return string
     */
    public function first(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function last(): string
    {
        return $this->lastName;
    }






}