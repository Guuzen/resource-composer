<?php

declare(strict_types=1);

namespace Guuzen\ResourceComposer\Tests;

use Guuzen\ResourceComposer\ResourceComposer;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ResourceComposer
     */
    protected $composer;

    protected function setUp(): void
    {
        $this->composer = new ResourceComposer();
    }

    abstract public function test(): void;
}
