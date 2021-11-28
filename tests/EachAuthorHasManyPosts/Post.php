<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

final class Post
{
    public function __construct(public string $id, public string $authorId)
    {
    }
}
