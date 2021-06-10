<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Group;

use Guuzen\ResourceComposer\Group;

final class GroupOfOne implements Group
{
    private string $groupBy;

    private ExtractArrayKey $extractArrayKey;

    public function __construct(string $groupBy, ?ExtractArrayKey $extractArrayKey = null)
    {
        $this->groupBy         = $groupBy;
        $this->extractArrayKey = $extractArrayKey ?? new ExtractArrayKey();
    }

    public function group(array $resources): array
    {
        $groups = [];
        foreach ($resources as $resource) {
            $mapId          = $this->extractArrayKey->extract($resource, $this->groupBy);
            $groups[$mapId] = $resource;
        }

        return $groups;
    }
}
