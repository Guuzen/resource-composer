<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use Guuzen\ResourceComposer\Config\DefaultMainResource;

final class Application extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasOne(
            resource: File::class,
            joinBy: 'fileA',
            joinTo: 'fileA',
            groupBy: 'id',
        );

        $this->hasOne(
            resource: File::class,
            joinBy: 'fileB',
            joinTo: 'fileB',
            groupBy: 'id',
        );
    }
}
