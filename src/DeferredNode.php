<?php

namespace Phive\Twig\Extensions\Deferred;

use Twig\Compiler;
use Twig\Node\Node;

class DeferredNode extends Node
{
    public function compile(Compiler $compiler)
    {
        $compiler
            ->write("\$this->env->getExtension('".DeferredExtension::class."')->resolve(\$this, \$context, \$blocks);\n")
        ;
    }
}
