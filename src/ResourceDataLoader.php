<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface ResourceDataLoader
{
    /**
     * @param array<array-key, string|int> $ids
     *
     * @return array<int, array>
     */
    public function load(array $ids): array;
}
