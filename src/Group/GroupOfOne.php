<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Group;

use Guuzen\ResourceComposer\Group;

final class GroupOfOne implements Group
{
    private ExtractArrayKey $extractArrayKey;

    public function __construct(?ExtractArrayKey $extractArrayKey = null)
    {
        $this->extractArrayKey = $extractArrayKey ?? new ExtractArrayKey();
    }

    public function group(array $resources, string $groupBy): array
    {
        $groups = [];
        foreach ($resources as $resource) {
            $mapId          = $this->extractArrayKey->extract($resource, $groupBy);
            $groups[$mapId] = $resource;
        }

        return $groups;
    }
}
