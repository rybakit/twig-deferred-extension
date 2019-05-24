<?php

namespace Phive\Twig\Extensions\Deferred;

use Twig\Environment;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

class DeferredNodeVisitor implements NodeVisitorInterface
{
    private $hasDeferred = false;

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node, Environment $env)
    {
        if (!$this->hasDeferred && $node instanceof DeferredBlockNode) {
            $this->hasDeferred = true;
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node, Environment $env)
    {
        if ($this->hasDeferred && $node instanceof ModuleNode) {
            $node->setNode('display_end', new Node([new DeferredNode(), $node->getNode('display_end')]));
            $this->hasDeferred = false;
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
