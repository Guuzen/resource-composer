<?php
declare(strict_types=1);

namespace Guuzen\ResourceComposer;

interface RelatedResource
{
    public function loader(): ResourceLoader;

    public function resource(): string;
}
