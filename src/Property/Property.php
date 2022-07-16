<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use JsonSerializable;
use SymfonyApiMapper\Enum\Visibility;

class Property implements JsonSerializable
{

    /** @var string */
    private $name;

    /** @var string */
    private $apiEqualsToName;

    /** @var bool */
    private $isNullable;

    /** @var Visibility */
    private $visibility;

    /** @var PropertyType[] */
    private $types = [];

    /**
     * @param string
     * @param string
     * @param Visibility
     * @param bool
     * @param PropertyType
     */
    public function __construct(
        string $name,
        string $apiEqualsToName,
        Visibility $visibility,
        bool $isNullable,
        PropertyType ...$types
    ) {
        $this->name = $name;
        $this->apiEqualsToName = $apiEqualsToName;
        $this->isNullable = $isNullable;
        $this->visibility = $visibility;
        $this->types = $types;
    }


    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return string */
    public function getApiEqualsToName(): string
    {
        return $this->apiEqualsToName;
    }

    
    /** @return PropertyType[] */
    public function getTypes(): array
    {
        return $this->types;
    }

    /** @return Visibility */
    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    /** @return bool*/
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /** @return int */
    public function isUnion(): bool
    {
        return \count($this->types) > 1;
    }

    /**
     * @return PropertyBuilder
     */
    public function asBuilder(): PropertyBuilder
    {
        return PropertyBuilder::new()
            ->setName($this->name)
            ->setApiEqualsToName($this->apiEqualsToName)
            ->setTypes(...$this->types)
            ->setIsNullable($this->isNullable())
            ->setVisibility($this->visibility);
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'apiEqualsToName' => $this->apiEqualsToName,
            'types' => $this->types,
            'visibility' => $this->visibility,
            'isNullable' => $this->isNullable,
        ];
    }

}
