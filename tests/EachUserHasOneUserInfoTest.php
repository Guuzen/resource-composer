<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;

final class EachUserHasOneUserInfoTest extends TestCase
{
    public function test(): void
    {
        $userId1     = '1';
        $userId2     = '2';
        $user1       = ['id' => $userId1];
        $user2       = ['id' => $userId2];
        $users       = [
            $user1,
            $user2,
        ];
        $userInfoId1 = 'nonsense';
        $userInfoId2 = 'nonsense';
        $userInfo1   = ['id' => $userInfoId1, 'userId' => $userId1];
        $userInfo2   = ['id' => $userInfoId2, 'userId' => $userId2];

        $this->composer->registerRelation(
            new MainResource('user', new SimpleCollector('id', 'userInfo')),
            new OneToOne(),
            new RelatedResource(
                'userInfo',
                'userId',
                new StubResourceDataLoader([$userInfo1, $userInfo2])
            ),
        );

        $resources = $this->composer->compose($users, 'user');

        self::assertEquals(
            [
                ['id' => $userId1, 'userInfo' => $userInfo1],
                ['id' => $userId2, 'userInfo' => $userInfo2],
            ],
            $resources,
        );
    }
}
