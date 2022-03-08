<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface ResourceLoader
{
    /**
     * @param array<int, string|int> $ids
     *
     * @return iterable<int, object>
     */
    public function load(array $ids, string $loadBy): iterable;
}
