<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use Guuzen\ResourceComposer\Tests\StubResourceLoader;
use Guuzen\ResourceComposer\Tests\TestCase;

final class EachApplicationHasOneDifferenFileTypesTest extends TestCase
{
    public function test(): void
    {
        $fileAId = '1';
        $fileBId = '2';
        $fileA   = [
            'id' => $fileAId,
        ];
        $fileB   = [
            'id' => $fileBId,
        ];

        $applicationId = 'nonsense';
        $application   = [
            'id'    => $applicationId,
            'fileA' => $fileAId,
            'fileB' => $fileBId,
        ];
        $applications  = [$application];

        $this->composer->registerMainResource(new Application());
        $this->composer->registerRelatedResource(
            new File(
                new StubResourceLoader([$fileA, $fileB])
            )
        );

        $resources = $this->composer->composeList($applications, Application::class);

        self::assertEquals(
            [
                [
                    'id'    => $applicationId,
                    'fileA' => $fileA,
                    'fileB' => $fileB,
                ]
            ],
            $resources,
        );
    }
}
