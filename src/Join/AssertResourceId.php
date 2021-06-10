<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Join;

final class AssertResourceId
{
    /**
     * @psalm-assert int|string|null $resourceId
     */
    public function assert(mixed $resourceId, string $idField): void
    {
        if (\is_string($resourceId) === false && \is_int($resourceId) === false && $resourceId !== null) {
            throw new \RuntimeException(
                \sprintf(
                    'Resource id in field %s must be int|string|null %s given',
                    $idField,
                    \gettype($resourceId),
                )
            );
        }
    }
}
