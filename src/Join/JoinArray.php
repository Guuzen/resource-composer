<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Join;

use Guuzen\ResourceComposer\Join;

final class JoinArray implements Join
{
    private string $joinBy;

    private string $joinTo;

    private AssertResourceId $assertResourceId;

    public function __construct(string $joinBy, string $joinTo, ?AssertResourceId $assertResourceId = null)
    {
        $this->joinBy           = $joinBy;
        $this->joinTo           = $joinTo;
        $this->assertResourceId = $assertResourceId ?? new AssertResourceId();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     */
    public function loadIds(\ArrayObject $resource): array
    {
        if (isset($resource[$this->joinBy]) === false) {
            return [];
        }

        $ids = $resource[$this->joinBy];

        if (\is_array($ids) === false) {
            throw new \RuntimeException(
                \sprintf(
                    'Resource must be joined by array of ids %s, %s given',
                    $this->joinBy,
                    \gettype($ids),
                )
            );
        }

        $loadIds = [];
        foreach ($ids as $id) {
            $this->assertResourceId->assert($id, $this->joinBy);
            $loadIds[] = $id;
        }

        return $loadIds;
    }

    public function resolve(\ArrayObject $resource, array $groups): void
    {
        if (isset($resource[$this->joinBy]) === false) {
            $resource[$this->joinTo] = [];

            return;
        }

        /** @var array<int|string|null> $ids */
        $ids = $resource[$this->joinBy];

        $values = [];
        foreach ($ids as $id) {
            if ($id === null || isset($groups[$id]) === false) {
                continue;
            }

            /** @psalm-suppress MixedAssignment */
            $values[] = $groups[$id];
        }

        $resource[$this->joinTo] = $values;
    }
}
