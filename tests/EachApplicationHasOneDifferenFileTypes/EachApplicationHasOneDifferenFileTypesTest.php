<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\EachApplicationHasOneDifferenFileTypes;

use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceComposer;
use PHPUnit\Framework\TestCase;

final class EachApplicationHasOneDifferenFileTypesTest extends TestCase
{
    public function test(): void
    {
        $fileAId = '1';
        $fileBId = '2';
        $fileA = new File($fileAId);
        $fileB = new File($fileBId);
        $applicationId = 'nonsense';
        $application = new Application($applicationId, $fileAId, $fileBId);
        $applications = [$application];

        $resolver = new ApplicationHasFilesResolver([$fileA, $fileB], new OneToOne());
        /** @psalm-suppress InvalidArgument */
        $composer = ResourceComposer::create([$resolver]);
        $composer->loadRelated($applications);

        $expectedApp = new Application($applicationId, $fileAId, $fileBId);
        $expectedApp->fileA = new File($fileAId);
        $expectedApp->fileB = new File($fileBId);


        self::assertEquals($expectedApp, $application);
    }
}
