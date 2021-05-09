<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\PromiseCollection;

final class Promise
{
    /**
     * @var callable(\ArrayObject): ?array-key
     */
    private $idExtractor;

    /**
     * @var callable(\ArrayObject, mixed): void
     */
    private $writer;

    /**
     * @var \ArrayObject
     */
    private $array;

    /**
     * @param callable(\ArrayObject): ?array-key $idExtractor
     * @param callable(\ArrayObject, mixed): void $writer
     */
    public function __construct($idExtractor, $writer, \ArrayObject $array)
    {
        $this->idExtractor = $idExtractor;
        $this->writer      = $writer;
        $this->array       = $array;
    }

    public function id(): int|string|null
    {
        return ($this->idExtractor)($this->array);
    }

    /**
     * @param mixed $value
     */
    public function resolve($value): void
    {
        ($this->writer)($this->array, $value);
    }
}
