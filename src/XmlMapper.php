<?php 

declare(strict_types=1);

namespace SymfonyApiMapper;

class XmlMapper implements MapperInterface
{
    /**
     * @param \stdClass $response
     * @param mixed $object 
     * @return string
     */
    public function map(\stdClass $response, $object)
    {
        echo 'This is xml mapper class';
    }
}