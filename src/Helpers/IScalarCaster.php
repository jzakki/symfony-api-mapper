<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Helpers;

use SymfonyApiMapper\Enum\ScalarType;

interface IScalarCaster
{
    /** @return string|bool|int|float */
    public function cast(ScalarType $scalarType, $value);
}
