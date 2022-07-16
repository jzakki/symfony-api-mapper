<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Entity;

class User
{
    public function __construct(){}

    /** @var string */
    private $full_name;

    /** @param string $full_name */
    public function setFull_Name($full_name): void
    {
        $this->full_name = $full_name;
    }

    /** @return string */
    public function getFull_Name(): string
    {
        return $this->full_name;
    }
}