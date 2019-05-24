<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

use Twig\Environment;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

class TestNodeVisitor implements NodeVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node, Environment $env)
    {
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node, Environment $env)
    {
        if ($node instanceof ModuleNode) {
            $node->setNode('display_end', new Node([new TestNode(), $node->getNode('display_end')]));
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
