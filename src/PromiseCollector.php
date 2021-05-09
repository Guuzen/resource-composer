<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

use Guuzen\ResourceComposer\PromiseCollection\Promise;

interface PromiseCollector
{
    /**
     * @return Promise[]
     */
    public function collect(\ArrayObject $resource): array;
}
