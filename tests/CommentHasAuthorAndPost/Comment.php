<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

final class Comment
{
    public Author $author;

    public Post $post;

    public function __construct(
        public string $id,
    )
    {
    }
}
