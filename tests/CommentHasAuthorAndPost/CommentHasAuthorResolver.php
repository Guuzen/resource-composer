<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CommentHasAuthorAndPost;

use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceResolver;

/**
 * @implements ResourceResolver<Comment, Author>
 */
final class CommentHasAuthorResolver implements ResourceResolver
{
    /**
     * @param array<int, Author> $loadedResources
     */
    public function __construct(private array $loadedResources, private OneToOne $oneToOne)
    {
    }

    public function extractIds(object $resource): \Traversable
    {
        yield $resource->id;
    }

    public function load(array $ids): array
    {
        return $this->loadedResources;
    }

    public function resolve(object $resource, array $loadedResources): void
    {
        $grouped = $this->oneToOne->group($loadedResources, fn(Author $author) => $author->commentId);

        $resource->author = $grouped[$resource->id];
    }

    public function resourceClass(): string
    {
        return Comment::class;
    }
}
