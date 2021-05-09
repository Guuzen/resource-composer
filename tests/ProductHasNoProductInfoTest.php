<?php

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\Config\MainResource;
use Guuzen\ResourceComposer\Config\RelatedResource;
use Guuzen\ResourceComposer\Link\OneToOne;
use Guuzen\ResourceComposer\PromiseCollector\SimpleCollector;

final class ProductHasNoProductInfoTest extends TestCase
{
    public function test(): void
    {
        $productId   = 'nonsense';
        $product     = ['id' => $productId];
        $productInfo = ['id' => '10'];

        $this->composer->registerRelation(
            new MainResource('product', new SimpleCollector('id', 'productInfo')),
            new OneToOne(),
            new RelatedResource('productInfo', 'id', new StubResourceDataLoader([$productInfo])),
        );

        $resources = $this->composer->composeOne($product, 'product');

        self::assertEquals(['id' => $productId, 'productInfo' => null], $resources);
    }
}
