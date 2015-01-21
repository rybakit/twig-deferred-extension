<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

class TestNode extends \Twig_Node
{
    public function compile(\Twig_Compiler $compiler)
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
