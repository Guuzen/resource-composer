<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceLoader;
use Guuzen\ResourceComposer\RelatedResource;

final class Post implements RelatedResource
{
    private ResourceLoader $loadResources;

    public function __construct(ResourceLoader $loadResources)
    {
        $this->loadResources = $loadResources;
    }

    public function loader(): ResourceLoader
    {
        return $this->loadResources;
    }

    public function resource(): string
    {
        return self::class;
    }
}
