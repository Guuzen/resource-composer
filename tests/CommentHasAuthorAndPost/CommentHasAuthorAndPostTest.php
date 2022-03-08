<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\ResourceComposer;
use PHPUnit\Framework\TestCase;

final class CommentHasAuthorAndPostTest extends TestCase
{
    public function test(): void
    {
        $commentId = '1';
        $comment = new Comment($commentId);
        $authorId = 'nonsense';
        $author = new Author($authorId, $commentId);
        $postId = 'nonsense';
        $post = new Post($postId, $commentId);


        $postLink = new CommentHasPost();
        $authorLink = new CommentHasAuthor();
        /** @psalm-suppress InvalidArgument */
        $composer = ResourceComposer::create([$postLink, $authorLink], [new AuthorLoader([$author]), new PostLoader([$post])]);

        $composer->loadRelated([$comment]);

        $expectedComment = new Comment($commentId);
        $expectedComment->post = new Post($postId, $commentId);
        $expectedComment->author = new Author($authorId, $commentId);

        self::assertEquals($expectedComment, $comment);
    }
}
