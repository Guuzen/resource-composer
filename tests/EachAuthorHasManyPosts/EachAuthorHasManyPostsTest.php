<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

use Guuzen\ResourceComposer\OneToMany;
use Guuzen\ResourceComposer\ResourceComposer;
use PHPUnit\Framework\TestCase;

final class EachAuthorHasManyPostsTest extends TestCase
{
    public function test(): void
    {
        $authorId1 = '1';
        $authorId2 = '2';
        $author1 = new Author($authorId1);
        $author2 = new Author($authorId2);
        $authors = [
            $author1,
            $author2,
        ];
        $postId1 = 'nonsense';
        $postId2 = 'nonsense';
        $post1 = new Post($postId1, $authorId1);
        $post2 = new Post($postId2, $authorId2);
        $posts = [
            $post1,
            $post2,
        ];

        $resolver = new AuthorHasManyPostsResolver($posts, new OneToMany());
        /** @psalm-suppress InvalidArgument */
        $composer = ResourceComposer::create([$resolver]);

        $composer->loadRelated($authors);

        $expectedAuthor1 = new Author($authorId1);
        $expectedAuthor1->posts[] = new Post($postId1, $authorId1);
        $expectedAuthor2 = new Author($authorId2);
        $expectedAuthor2->posts[] = new Post($postId2, $authorId2);

        self::assertEquals([$expectedAuthor1, $expectedAuthor2], $authors);
    }
}
