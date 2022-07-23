<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Entity;

class Password
{
    /** @var string $hash */
    private $hash;

    public function __construct(){}

    /** @param string $hash */
    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    /** @return string */
    public function getHash(): string
    {
        return $this->hash;
    }
}