<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Config;

use Guuzen\ResourceComposer\PromiseCollector;

/**
 * @psalm-immutable
 */
final class MainResource
{
    public $name;

    public $collector;

    public function __construct(string $name, PromiseCollector $collector)
    {
        $this->name      = $name;
        $this->collector = $collector;
    }
}
