<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

use Twig\Compiler;
use Twig\Node\Node;

class TestNode extends Node
{
    public function compile(Compiler $compiler)
    {
        $compiler
            ->write("if (isset(\$context['body_extra'])) {\n")
            ->indent()
            ->write("echo \$context['body_extra'];\n")
            ->outdent()
            ->write("}\n")
        ;
    }
}
