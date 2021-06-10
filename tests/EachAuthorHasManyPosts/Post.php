<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

use Guuzen\ResourceComposer\ResourceLoader;
use Guuzen\ResourceComposer\RelatedResource;

final class Post implements RelatedResource
{
    private ResourceLoader $postLoader;

    public function __construct(ResourceLoader $postLoader)
    {
        $this->postLoader = $postLoader;
    }

    public function getLoader(): ResourceLoader
    {
        return $this->postLoader;
    }

    public function getResource(): string
    {
        return self::class;
    }
}