<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Config;

use Guuzen\ResourceComposer\Group\GroupOfMany;
use Guuzen\ResourceComposer\Group\GroupOfOne;
use Guuzen\ResourceComposer\Join\JoinArray;
use Guuzen\ResourceComposer\Join\JoinOne;
use Guuzen\ResourceComposer\MainResource;

abstract class DefaultMainResource implements MainResource
{
    /**
     * @var array<int, Link>
     */
    private $links = [];

    protected function hasOne(string $resource, string $joinBy, string $joinTo): void
    {
        $this->links[] = new Link(
            static::class,
            $resource,
            new GroupOfOne(),
            new JoinOne($joinBy, $joinTo),
        );
    }

    protected function hasMany(string $resource, string $joinBy, string $joinTo): void
    {
        $this->links[] = new Link(
            static::class,
            $resource,
            new GroupOfMany(),
            new JoinOne($joinBy, $joinTo, []),
        );
    }

    protected function hasArray(string $resource, string $joinBy, string $joinTo): void
    {
        $this->links[] = new Link(
            static::class,
            $resource,
            new GroupOfOne(),
            new JoinArray($joinBy, $joinTo),
        );
    }

    abstract protected function config(): void;

    public function configs(): array
    {
        $this->config();

        return $this->links;
    }
}
