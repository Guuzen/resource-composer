<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

use Guuzen\ResourceComposer\OneToOne;
use Guuzen\ResourceComposer\ResourceResolver;

/**
 * @implements ResourceResolver<Customer, Order>
 */
final class CustomerHasArrayOfOrdersResolver implements ResourceResolver
{
    /**
     * @param array<int, Order> $orders
     */
    public function __construct(private array $orders, private OneToOne $oneToOne)
    {
    }

    public function extractIds(object $resource): \Traversable
    {
        yield from $resource->ordersIds;
    }

    public function load(array $ids): array
    {
        return $this->orders;
    }

    public function resolve(object $resource, array $loadedResources): void
    {
        $grouped = $this->oneToOne->group($loadedResources, fn (Order $order) => $order->id);
        foreach ($resource->ordersIds as $orderId) {
            if (!isset($grouped[$orderId])) {
                continue;
            }
            $resource->orders[] = $grouped[$orderId];
        }
    }

    public function resourceClass(): string
    {
        return Customer::class;
    }
}
