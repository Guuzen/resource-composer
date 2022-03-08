<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceLoader;

final class AuthorLoader implements ResourceLoader
{
    /**
     * @param array<int, Author> $authors
     */
    public function __construct(private array $authors)
    {
    }

    public function load(array $ids, string $loadBy): iterable
    {
        return $this->authors;
    }
}
