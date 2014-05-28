<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredBlockReferenceNode extends \Twig_Node_BlockReference
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("\$this->env->getExtension('deferred')->defer(\$this, '%s');\n", $this->getAttribute('name')))
        ;
    }
}
