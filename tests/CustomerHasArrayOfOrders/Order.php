<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests\CustomerHasArrayOfOrders;

use Guuzen\ResourceComposer\ResourceLoader;
use Guuzen\ResourceComposer\RelatedResource;

final class Order implements RelatedResource
{
    private ResourceLoader $orderLoader;

    public function __construct(ResourceLoader $orderLoader)
    {
        $this->orderLoader = $orderLoader;
    }

    public function getLoader(): ResourceLoader
    {
        return $this->orderLoader;
    }

    public function getResource(): string
    {
        return self::class;
    }
}
