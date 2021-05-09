<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface Link
{
    /**
     * @param array<array-key, \ArrayObject> $resources
     *
     * @return array<array-key, mixed>
     */
    public function group(array $resources, int|string $key): array;

    /**
     * @return mixed
     * TODO
     */
    public function defaultEmptyValue();
}
