<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TestExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals()
    {
        return ['data' => new \ArrayObject()];
    }

    public function getNodeVisitors()
    {
        return [new TestNodeVisitor()];
    }
}
