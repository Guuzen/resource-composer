<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

final class Author
{
    public function __construct(
        public string $id,
        public string $commentId,
    )
    {
    }
}
