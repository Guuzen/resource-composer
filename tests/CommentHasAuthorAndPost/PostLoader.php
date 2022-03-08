<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceLoader;

final class PostLoader implements ResourceLoader
{
    /**
     * @param array<int, Post> $posts
     */
    public function __construct(private array $posts)
    {
    }

    public function load(array $ids, string $loadBy): iterable
    {
        return $this->posts;
    }
}
