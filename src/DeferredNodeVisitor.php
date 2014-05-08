<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredNodeVisitor implements \Twig_NodeVisitorInterface
{
    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return $node;
    }

    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return ($node instanceof \Twig_Node_Module)
            ? new DeferredModuleNode($node)
            : $node;
    }

    public function getPriority()
    {
        return 0;
    }
}
