<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static self PUBLIC()
 * @method static self PROTECTED()
 * @method static self PRIVATE()
 */
class Visibility extends Enum
{
    private const PUBLIC = 'public';
    private const PROTECTED = 'protected';
    private const PRIVATE = 'private';
    
    public static function fromReflectionProperty(\ReflectionProperty $property): self
    {
        if ($property->isPublic()) {
            return self::PUBLIC();
        }
        if ($property->isProtected()) {
            return self::PROTECTED();
        }
        return self::PRIVATE();
    }
}