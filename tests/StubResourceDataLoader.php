<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\ResourceDataLoader;

class StubResourceDataLoader implements ResourceDataLoader
{
    private $resources;

    /**
     * @param array<int, array> $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    public function load(array $ids): array
    {
        return $this->resources;
    }
}
