<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToMany;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;

final class CustomerHasOrdersWhichHasDetailsTest extends TestCase
{
    public function test(): void
    {
        $customerId   = '1';
        $customer     = [
            'id' => $customerId,
        ];
        $orderId      = '2';
        $order        = [
            'id'         => $orderId,
            'customerId' => $customerId,
        ];
        $orderDetails = [
            'id' => $orderId,
        ];

        $this->composer->registerRelation(
            new MainResource('customer', new SimpleCollector('id', 'orders')),
            new OneToMany(),
            new RelatedResource('order', 'customerId', new StubResourceDataLoader([$order])),
        );
        $this->composer->registerRelation(
            new MainResource('order', new SimpleCollector('id', 'details')),
            new OneToOne(),
            new RelatedResource('orderDetails', 'id', new StubResourceDataLoader([$orderDetails])),
        );

        $resource = $this->composer->composeOne($customer, 'customer');

        self::assertEquals(
            [
                'id'     => $customerId,
                'orders' => [
                    [
                        'id'         => $orderId,
                        'customerId' => $customerId,
                        'details'    => $orderDetails,
                    ]
                ],

            ],
            $resource,
        );
    }
}
