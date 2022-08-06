<?php

namespace Php\App;

class Users
{
    protected ?int $id;
    public ?string $name;
    public ?string $surname;


    public function __construct(int $id = null, string $name = null, string $surname = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
    }

    public function __toString(): string
    {
        return "User $this->id с именем $this->name и фамилией $this->surname." . PHP_EOL;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $username
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $username
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }
}
