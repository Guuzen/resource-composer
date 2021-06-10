<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use Guuzen\ResourceComposer\ResourceLoader;
use Guuzen\ResourceComposer\RelatedResource;

final class File implements RelatedResource
{
    private ResourceLoader $fileLoader;

    public function __construct(ResourceLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    public function loader(): ResourceLoader
    {
        return $this->fileLoader;
    }

    public function resource(): string
    {
        return self::class;
    }
}
