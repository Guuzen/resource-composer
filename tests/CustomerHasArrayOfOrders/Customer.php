<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

use Guuzen\ResourceComposer\Config\DefaultMainResource;

final class Customer extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasArray(
            resource: Order::class,
            joinBy: 'ordersIds',
            joinTo: 'orders',
        );
    }
}
