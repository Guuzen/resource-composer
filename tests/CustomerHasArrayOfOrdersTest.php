<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\ArrayCollector;
use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;

final class CustomerHasArrayOfOrdersTest extends TestCase
{
    public function test(): void
    {
        $orderId1    = '1';
        $orderPrice1 = '111';
        $order1      = ['id' => $orderId1, 'price' => $orderPrice1];
        $orderId2    = '2';
        $orderPrice2 = '222';
        $order2      = ['id' => $orderId2, 'price' => $orderPrice2];

        $cutomerId = '1';
        $customer  = [
            'id'        => $cutomerId,
            'ordersIds' => [
                $orderId1,
                $orderId2,
            ],
        ];

        $this->composer->registerRelation(
            new MainResource('customer', new ArrayCollector('ordersIds', 'orders')),
            new OneToOne(),
            new RelatedResource('order', 'id', new StubResourceDataLoader([$order1, $order2])),
        );

        $resource = $this->composer->composeOne($customer, 'customer');

        self::assertEquals(
            [
                'id'        => $cutomerId,
                'ordersIds' => [
                    $orderId1,
                    $orderId2,
                ],
                'orders'    => [
                    $order1,
                    $order2
                ],
            ],
            $resource
        );
    }
}
