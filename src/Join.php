<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface Join
{
    /**
     * @psalm-return array<int, int|string|null>
     */
    public function loadIds(\ArrayObject $resource): array;

    public function resolve(\ArrayObject $resource, array $groups): void;
}
