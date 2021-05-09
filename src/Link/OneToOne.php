<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Link;

use Guuzen\ResourceComposer\Link;

final class OneToOne implements Link
{
    public function group(array $resources, int|string $key): array
    {
        $map = [];
        foreach ($resources as $resource) {
            /** @psalm-suppress MixedAssignment */
            $mapId = $resource[$key];
            if (\is_string($mapId) === false) {
                throw new \RuntimeException(
                    \sprintf('Resource group key must be a string %s given', \gettype($mapId))
                );
            }
            $map[$mapId] = $resource;
        }

        return $map;
    }

    public function defaultEmptyValue(): void
    {
    }
}
