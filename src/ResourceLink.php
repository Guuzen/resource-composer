<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface ResourceLink
{
    /**
     * @return class-string
     */
    public function loaderClass(): string;

    public function resolver(): ResourceResolver;

    /**
     * @return class-string
     */
    public function resourceClass(): string;
}
