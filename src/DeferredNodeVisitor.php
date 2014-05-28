<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredNodeVisitor implements \Twig_NodeVisitorInterface
{
    private $hasDeferred = false;

    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        if (!$this->hasDeferred && $node instanceof DeferredBlockReferenceNode) {
            $this->hasDeferred = true;
        }

        return $node;
    }

    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        if ($this->hasDeferred && $node instanceof \Twig_Node_Module) {
            $node = new DeferredModuleNode($node);
            $this->hasDeferred = false;
        }

        return $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
