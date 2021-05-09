<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Link;

use Guuzen\ResourceComposer\Link;

final class OneToMany implements Link
{
    public function group(array $resources, int|string $key): array
    {
        $groups = [];
        foreach ($resources as $resource) {
            /** @psalm-suppress MixedAssignment */
            $groupId = $resource[$key];
            if (\is_string($groupId) === false) {
                throw new \RuntimeException(
                    \sprintf('Resource group key must be a string %s given', \gettype($groupId))
                );
            }
            $groups[$groupId][] = $resource;
        }

        return $groups;
    }

    public function defaultEmptyValue(): array
    {
        return [];
    }
}
