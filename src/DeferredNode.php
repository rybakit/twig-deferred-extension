<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredNode extends \Twig_Node
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->write("\$this->env->getExtension('".DeferredExtension::class."')->resolve(\$this, \$context, \$blocks);\n")
        ;
    }
}
