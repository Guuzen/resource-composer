<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

use Guuzen\ResourceComposer\Config\Link;

interface MainResource
{
    /**
     * @return array<int, Link>
     */
    public function configs(): array;
}
