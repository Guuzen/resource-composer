<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

use Guuzen\ResourceComposer\ResourceResolver;
use Guuzen\ResourceComposer\OneToMany;
use Guuzen\ResourceComposer\ResourceLink;

final class AuthorHasManyPosts implements ResourceLink
{
    public function loaderClass(): string
    {
        return PostLoader::class;
    }

    public function resolver(): ResourceResolver
    {
        return new OneToMany('id', 'authorId', 'posts');
    }

    public function resourceClass(): string
    {
        return Author::class;
    }
}
