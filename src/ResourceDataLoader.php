<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface ResourceDataLoader
{
    /**
     * @param string[] $ids
     *
     * @return array<int, array>
     */
    public function load(array $ids): array;
}
