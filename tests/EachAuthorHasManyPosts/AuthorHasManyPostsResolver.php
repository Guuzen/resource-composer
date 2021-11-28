<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

use Guuzen\ResourceComposer\OneToMany;
use Guuzen\ResourceComposer\ResourceResolver;

/**
 * @implements ResourceResolver<Author, Post>
 */
final class AuthorHasManyPostsResolver implements ResourceResolver
{
    /**
     * @param array<int, Post> $posts
     */
    public function __construct(private array $posts, private OneToMany $oneToMany)
    {
    }

    public function extractIds(object $resource): \Traversable
    {
        yield $resource->id;
    }

    public function load(array $ids): array
    {
        return $this->posts;
    }

    public function resolve(object $resource, array $loadedResources): void
    {
        $grouped = $this->oneToMany->group($loadedResources, fn(Post $post) => $post->authorId);

        $resource->posts = $grouped[$resource->id];
    }

    public function resourceClass(): string
    {
        return Author::class;
    }
}
