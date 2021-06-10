<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\ResourceLoader;

final class StubResourceLoader implements ResourceLoader
{
    private $resources;

    private string $loadBy;

    /**
     * @param array<int, array> $resources
     */
    public function __construct(array $resources, string $loadBy)
    {
        $this->resources = $resources;
        $this->loadBy    = $loadBy;
    }

    public function load(array $ids): array
    {
        return $this->resources;
    }

    public function loadBy(): string
    {
        return $this->loadBy;
    }
}
