<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface ResourceLoader
{
    /**
     * @param array<int, string|int> $ids
     *
     * @return array<int, array>
     */
    public function load(array $ids): array;
}
