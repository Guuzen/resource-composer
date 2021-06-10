<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\ResourceLoader;

final class StubResourceLoader implements ResourceLoader
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
