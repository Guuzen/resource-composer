<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Config;

use Guuzen\ResourceComposer\ResourceDataLoader;

/**
 * @psalm-immutable
 */
final class RelatedResource
{
    public $name;

    public $linkKey;

    public $loader;

    public function __construct(string $name, int|string $linkKey, ResourceDataLoader $loader)
    {
        $this->name    = $name;
        $this->linkKey = $linkKey;
        $this->loader  = $loader;
    }
}
