<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\Config\DefaultMainResource;

final class Comment extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasOne(
            resource: Author::class,
            joinBy: 'id',
            joinTo: 'author',
            groupBy: 'commentId',
        );

        $this->hasOne(
            resource: Post::class,
            joinBy: 'id',
            joinTo: 'post',
            groupBy: 'commentId',
        );
    }
}
