<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

class Property
{

    /** @var string */
    private $name;

    /** @var bool */
    private $isNullable;

    /** @var string */
    private $visibility;

    /** @var PropertyType[] */
    private $types = [];

    /**
     * @param string
     * @param string
     * @param bool
     * @param PropertyType
     */
    public function __construct(
        string $name,
        string $visibility,
        bool $isNullable,
        PropertyType ...$types
    ) {
        $this->name = $name;
        $this->isNullable = $isNullable;
        $this->visibility = $visibility;
        $this->types = $types;
    }

    /** @param string */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @param PropertyType[] */
    public function setTypes(PropertyType ...$types): self
    {
        $this->types = $types;
        return $this;
    }

    /** @return PropertyType[] */
    public function getTypes(): array
    {
        return $this->propertyTypes;
    }

    /** @param string */
    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;
        return $this;
    }

    /** @return string */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /** @param bool */
    public function setIsNullable(bool $isNullable): self
    {
        $this->isNullable = $isNullable;
        return $this;
    }

    /** @return bool*/
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /** @return int */
    public function isUnion(): bool
    {
        return \count($this->propertyTypes) > 1;
    }

}
