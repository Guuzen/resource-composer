<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;
use Guuzen\ResourceComposer\Tests\TestCase;

final class CommentHasAuthorAndPostTest extends TestCase
{
    public function test(): void
    {
        $commentId = '1';
        $comment   = ['id' => $commentId];
        $authorId  = '1';
        $author    = [
            'id'        => $authorId,
            'commentId' => $commentId
        ];
        $postId    = '1';
        $post      = [
            'id'        => $postId,
            'commentId' => $commentId,
        ];

        $this->composer->registerRelation(
            new MainResource('comment', new SimpleCollector('id', 'author')),
            new OneToOne(),
            new RelatedResource('author', 'id', new AuthorsLoader([$author])),
        );
        $this->composer->registerRelation(
            new MainResource('comment', new SimpleCollector('id', 'post')),
            new OneToOne(),
            new RelatedResource('post', 'id', new PostsLoader([$post])),
        );

        $resource = $this->composer->composeOne($comment, 'comment');

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
