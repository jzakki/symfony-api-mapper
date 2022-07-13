<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Entity;

class User
{
    /** @var string */
    private string $name;

    /** @param string $name */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }
}