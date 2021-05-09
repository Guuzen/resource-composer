<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\PromiseCollector;

use Guuzen\ResourceComposer\PromiseCollection\Promise;
use Guuzen\ResourceComposer\PromiseCollector;

final class ArrayCollector implements PromiseCollector
{
    private $arrayOfKeys;

    private $writeKey;

    public function __construct(string $arrayOfKeys, string $writeKey)
    {
        $this->arrayOfKeys = $arrayOfKeys;
        $this->writeKey    = $writeKey;
    }

    /**
     * @inheritDoc
     */
    public function collect(\ArrayObject $resource): array
    {
        $promises = [];

        $keys = $resource[$this->arrayOfKeys] ??
            throw new PromiseCollectionError(
                \sprintf('Array of keys %s in main resource must exist', $this->arrayOfKeys)
            );

        if (\is_array($keys) === false) {
            throw new PromiseCollectionError(
                \sprintf('Array of keys %s in main resource must be array', $this->arrayOfKeys)
            );
        }

        /** @psalm-suppress MixedAssignment */
        foreach ($keys as $index => $key) {
            $promises[] = new Promise(
            /**
             * @psalm-suppress MixedInferredReturnType
             * @psalm-suppress UnusedClosureParam
             */
                function (\ArrayObject $resource) use ($key): string|int {
                    /** @psalm-suppress MixedReturnStatement */
                    return $key;
                },
                function (\ArrayObject $customer, mixed $writeValue) use ($index): void {
                    if (isset($customer[$this->writeKey]) === false) {
                        /**
                         * @psalm-suppress MixedArrayAssignment
                         * @psalm-suppress MixedAssignment
                         */
                        $customer[$this->writeKey][$index] = $writeValue;

                        return;
                    }

                    if (\is_array($customer[$this->writeKey]) === true) {
                        /**
                         * @psalm-suppress MixedAssignment
                         */
                        $customer[$this->writeKey][$index] = $writeValue;

                        return;
                    }

                    throw new PromiseCollectionError(
                        \sprintf('Value in write key %s in main resource must be array', $this->writeKey)
                    );
                },
                $resource
            );
        }

        return $promises;
    }
}
