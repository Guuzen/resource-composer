<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\MergeCollector;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;

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

        $this->composer->registerRelation(
            new MainResource(
                'application',
                new MergeCollector(
                    [
                        new SimpleCollector('fileA', 'fileA'),
                        new SimpleCollector('fileB', 'fileB'),
                    ],
                ),
            ),
            new OneToOne(),
            new RelatedResource('file', 'id', new StubResourceDataLoader([$fileA, $fileB])),
        );

        $resources = $this->composer->compose($applications, 'application');

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
