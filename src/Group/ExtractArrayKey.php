<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Group;

final class ExtractArrayKey
{
    public function extract(\ArrayObject $array, string $field): int|string
    {
        $key = $array[$field];
        if (\is_string($key) === false && \is_int($key) === false) {
            throw new \RuntimeException(
                \sprintf(
                    'Group by field of %s must be int|string %s given',
                    $field,
                    \gettype($key),
                )
            );
        }

        return $key;
    }
}
