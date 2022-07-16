<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

class PropertyType
{
    /** @var string */
    private $type;
    /** @var bool */
    private $isArray;

    public function __construct(string $type, bool $isArray)
    {
        $this->type = $type;
        $this->isArray = $isArray;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'isArray' => $this->isArray,
        ];
    }
}