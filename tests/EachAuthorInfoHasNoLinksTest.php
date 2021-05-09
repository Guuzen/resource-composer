<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

final class EachAuthorInfoHasNoLinksTest extends TestCase
{
    public function test(): void
    {
        $authorInfoId1 = '1';
        $authorInfo1   = ['id' => $authorInfoId1];

        $authorInfoId2 = '2';
        $authorInfo2   = ['id' => $authorInfoId2];

        $authorsInfo = [
            $authorInfo1,
            $authorInfo2,
        ];

        $resources = $this->composer->compose($authorsInfo, 'authorInfo');

        self::assertEquals($authorsInfo, $resources);
    }
}
