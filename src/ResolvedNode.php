<?php

namespace Phive\Twig\Extensions\Deferred;

class ResolvedNode extends \Twig_Node
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->write("\$deferred = \$this->env->getExtension('deferred');\n")
            ->write("\$template = \$this;\n")
            ->raw("\n")
            ->write("while (\$template) {\n")
            ->indent()
            ->write("\$deferred->resolve(\$template);\n")
            ->write("\$template = \$template->getParent(\$context);")
            ->outdent()
            ->write("}\n")
        ;
    }
}
