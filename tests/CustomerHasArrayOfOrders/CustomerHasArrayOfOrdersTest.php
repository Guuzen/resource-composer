<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

use Guuzen\ResourceComposer\Tests\StubResourceLoader;
use Guuzen\ResourceComposer\Tests\TestCase;

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

        $this->composer->registerMainResource(new Customer());
        $this->composer->registerRelatedResource(
            new Order(
                new StubResourceLoader([$order1, $order2], 'id')
            )
        );

        $resource = $this->composer->compose($customer, Customer::class);

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
