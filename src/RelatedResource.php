<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface RelatedResource
{
    public function getLoader(): ResourceLoader;

    public function getResource(): string;
}
