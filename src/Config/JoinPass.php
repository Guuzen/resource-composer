<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Config;

final class JoinPass
{
    public string $relatedResource;

    /**
     * @var array<string, array<int, Link>>
     */
    public array $links = [];

    public function __construct(string $relatedResource)
    {
        $this->relatedResource = $relatedResource;
    }

    public function addLink(Link $link): void
    {
        $this->links[$link->mainResource][] = $link;
    }
}
