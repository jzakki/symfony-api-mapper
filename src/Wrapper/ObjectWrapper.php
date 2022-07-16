<?php

declare(strict_types=1);

namespace SymfonyApiMapper\Wrapper;

use SymfonyApiMapper\Exception\TypeException;

class ObjectWrapper
{
    /** @var object */
    private $object;

    /** @var \ReflectionClass|null */
    private $reflectedObject;

    /** @param object $object */
    public function __construct($object)
    {
        if(! \is_object($object)){
            throw TypeException::forArgument(__METHOD__, 'object', $object, 1, '$object');
        }

        $this->object = $object;
    }

    /** @return object */
    public function getObject()
    {
        return $this->object;
    }

    /** @return \ReflectionClass */
    public function getReflectedObject(): \ReflectionClass
    {
        if($this->reflectedObject === null){
            $this->reflectedObject = new \ReflectionClass($this->object);
        }

        return $this->reflectedObject;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->getReflectedObject()->getName();
    }


}