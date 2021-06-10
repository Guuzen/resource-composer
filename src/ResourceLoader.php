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

    /**
     * Field by which resource will be loaded.
     * Must present in every returned resource from load
     */
    public function loadBy(): string;
}
