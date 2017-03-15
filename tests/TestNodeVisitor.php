<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

class TestNodeVisitor implements \Twig_NodeVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function enterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Module) {
            $node->setNode('display_end', new \Twig_Node([new TestNode(), $node->getNode('display_end')]));
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
