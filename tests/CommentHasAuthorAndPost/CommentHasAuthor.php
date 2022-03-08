<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceResolver;
use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceLink;

final class CommentHasAuthor implements ResourceLink
{
    public function loaderClass(): string
    {
        return AuthorLoader::class;
    }

    public function resolver(): ResourceResolver
    {
        return new OneToOne('id', 'commentId', 'author');
    }

    public function resourceClass(): string
    {
        return Comment::class;
    }
}
