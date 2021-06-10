<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceLoader;
use Guuzen\ResourceComposer\RelatedResource;

final class Author implements RelatedResource
{
    private ResourceLoader $loadResources;

    public function __construct(ResourceLoader $loadResources)
    {
        $this->loadResources = $loadResources;
    }

    public function getLoader(): ResourceLoader
    {
        return $this->loadResources;
    }

    public function getResource(): string
    {
        return self::class;
    }
}
