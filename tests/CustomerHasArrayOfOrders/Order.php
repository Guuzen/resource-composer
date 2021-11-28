<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

final class Order
{
    public function __construct(
        public string $id,
        public string $price
    )
    {
    }
}
