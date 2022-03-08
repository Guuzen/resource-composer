<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceResolver;
use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceLink;

final class CommentHasPost implements ResourceLink
{
    public function loaderClass(): string
    {
        return PostLoader::class;
    }

    public function resolver(): ResourceResolver
    {
        return new OneToOne('id', 'commentId', 'post');
    }

    public function resourceClass(): string
    {
        return Comment::class;
    }
}
