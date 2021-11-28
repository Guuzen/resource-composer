<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachApplicationHasOneDifferenFileTypes;

final class Application
{
    public File $fileA;

    public File $fileB;

    public function __construct(
        public string $id,
        public string $fileAId,
        public string $fileBId,
    )
    {
    }
}
