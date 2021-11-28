<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

final class Customer
{
    /**
     * @var list<Order>
     */
    public array $orders;

    /**
     * @param list<string> $ordersIds
     */
    public function __construct(
        public string $id,
        public array $ordersIds
    )
    {
    }
}
