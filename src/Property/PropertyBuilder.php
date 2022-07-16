<?php 

declare(strict_types=1);

namespace SymfonyApiMapper\Property;

use SymfonyApiMapper\Enum\Visibility;
use SymfonyApiMapper\Property\Property;
use SymfonyApiMapper\Property\PropertyType;

class PropertyBuilder
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

    public static function new(): self
    {
        return new self();
    }

    public function build(): Property
    {
        return new Property(
            $this->name,
            $this->apiEqualsToName,
            $this->visibility,
            $this->isNullable,
            ...$this->types
        );
    }

    /** @param string */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

     /** @param string */
     public function setApiEqualsToName(string $apiEqualsToName): self
     {
         $this->apiEqualsToName = $apiEqualsToName;
         return $this;
     }

    /** @param PropertyType[] */
    public function setTypes(PropertyType ...$types): self
    {
        $this->types = $types;
        return $this;
    }
    
    /** @param Visibility */
    public function setVisibility(Visibility $visibility): self
    {
        $this->visibility = $visibility;
        return $this;
    }

    /** @param bool */
    public function setIsNullable(bool $isNullable): self
    {
        $this->isNullable = $isNullable;
        return $this;
    }


    public function addType(string $type, bool $isArray): self
    {
        $this->types[] = new PropertyType($type, $isArray);
        return $this;
    }

}