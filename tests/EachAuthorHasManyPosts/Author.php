<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachAuthorHasManyPosts;

use Guuzen\ResourceComposer\Config\DefaultMainResource;

final class Author extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasMany(
            resource: Post::class,
            joinBy: 'id',
            joinTo: 'posts',
        );
    }
}
