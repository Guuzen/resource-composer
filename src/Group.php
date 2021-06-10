<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface Group
{
    /**
     * @param \ArrayObject[] $resources
     */
    public function group(array $resources, string $groupBy): array;
}
