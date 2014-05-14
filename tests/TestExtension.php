<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

class TestExtension extends \Twig_Extension
{
    public function getGlobals()
    {
        return array('data' => new \ArrayObject());
    }

    public function getNodeVisitors()
    {
        return array(new TestNodeVisitor());
    }

    public function getName()
    {
        return 'test';
    }
}
