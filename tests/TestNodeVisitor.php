<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

class TestNodeVisitor implements \Twig_NodeVisitorInterface
{
    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return $node;
    }

    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return ($node instanceof \Twig_Node_Module)
            ? new TestModuleNode($node)
            : $node;
    }

    public function getPriority()
    {
        return -10;
    }
}
