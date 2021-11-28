<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

final class Author
{
    /**
     * @var array<int, Post>
     */
    public array $posts;

    public function __construct(public string $id)
    {
    }
}
