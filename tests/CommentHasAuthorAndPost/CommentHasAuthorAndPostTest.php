<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\Tests\StubResourceLoader;
use Guuzen\ResourceComposer\Tests\TestCase;

final class CommentHasAuthorAndPostTest extends TestCase
{
    public function test(): void
    {
        $commentId = '1';
        $comment   = ['id' => $commentId];
        $authorId  = 'nonsense';
        $author    = [
            'id'        => $authorId,
            'commentId' => $commentId
        ];
        $postId    = 'nonsense';
        $post      = [
            'id'        => $postId,
            'commentId' => $commentId,
        ];

        $this->composer->registerMainResource(new Comment());
        $this->composer->registerRelatedResource(
            new Author(
                new StubResourceLoader([$author], 'commentId'),
            ),
        );
        $this->composer->registerRelatedResource(
            new Post(
                new StubResourceLoader([$post], 'commentId'),
            ),
        );

        $resource = $this->composer->compose($comment, Comment::class);

        self::assertEquals(
            [
                'id'     => $commentId,
                'author' => $author,
                'post'   => $post,
            ],
            $resource,
        );
    }
}
