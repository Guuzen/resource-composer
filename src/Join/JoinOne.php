<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Join;

use Guuzen\ResourceComposer\Join;

final class JoinOne implements Join
{
    private string $joinBy;

    private string $joinTo;

    private mixed $defaultValue;

    private AssertResourceId $assertResourceId;

    public function __construct(string $joinBy, string $joinTo, mixed $defaultValue = null, ?AssertResourceId $assertResourceId = null)
    {
        $this->joinBy           = $joinBy;
        $this->joinTo           = $joinTo;
        $this->defaultValue     = $defaultValue;
        $this->assertResourceId = $assertResourceId ?? new AssertResourceId();
    }

    public function loadIds(\ArrayObject $resource): array
    {
        if (isset($resource[$this->joinBy]) === false) {
            return [];
        }

        $id = $resource[$this->joinBy];
        $this->assertResourceId->assert($id, $this->joinBy);

        return [$id];
    }

    public function resolve(\ArrayObject $resource, array $groups): void
    {
        if (isset($resource[$this->joinBy]) === false) {
            /** @psalm-suppress MixedAssignment */
            $resource[$this->joinTo] = $this->defaultValue;

            return;
        }

        /** @var int|string|null $id */
        $id = $resource[$this->joinBy];

        /** @psalm-suppress MixedAssignment */
        $resource[$this->joinTo] = $groups[$id] ?? $this->defaultValue;
    }
}
