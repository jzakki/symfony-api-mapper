<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Entity;

class User
{

    /** @var array */
    private $full_name;

    /** @var Password */
    private $password;

    public function __construct(){}

    /** @param array $full_name */
    public function setFull_Name($full_name): void
    {
        $this->full_name = $full_name;
    }

    /** @return array */
    public function getFull_Name(): array
    {
        return $this->full_name;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    /** @return Password */
    public function getPassword(): Password
    {
        return $this->password;
    }
}