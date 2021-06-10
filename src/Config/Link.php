<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Config;

use Guuzen\ResourceComposer\Group;
use Guuzen\ResourceComposer\Join;

/**
 * @psalm-immutable
 */
final class Link
{
    public string $mainResource;

    public string $relatedResource;

    public string $groupBy;

    public Group $group;

    public Join $join;

    public function __construct(string $mainResource, string $relatedResource, Group $group, Join $join)
    {
        $this->mainResource    = $mainResource;
        $this->relatedResource = $relatedResource;
        $this->group           = $group;
        $this->join            = $join;
    }
}
