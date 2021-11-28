<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceComposer;
use PHPUnit\Framework\TestCase;

final class CustomerHasArrayOfOrdersTest extends TestCase
{
    public function test(): void
    {
        $orderId1 = '1';
        $orderPrice1 = '111';
        $order1 = new Order($orderId1, $orderPrice1);
        $orderId2 = '2';
        $orderPrice2 = '222';
        $order2 = new Order($orderId2, $orderPrice2);

        $customerId = '1';
        $customer = new Customer($customerId, [
            $orderId1,
            $orderId2,
        ]);

        $resolver = new CustomerHasArrayOfOrdersResolver([$order1, $order2], new OneToOne());
        /** @psalm-suppress InvalidArgument */
        $composer = ResourceComposer::create([$resolver]);
        $composer->loadRelated([$customer]);

        $expectedComment = new Customer($customerId, [$orderId1, $orderId2]);
        $expectedComment->orders = [$order1, $order2];

        self::assertEquals($expectedComment, $customer);
    }
}
