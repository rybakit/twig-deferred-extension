<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredModuleNode extends \Twig_Node_Module
{
    public function __construct(\Twig_Node_Module $node)
    {
        \Twig_Node::__construct($node->nodes, $node->attributes, $node->lineno, $node->tag);
    }

    protected function compileDisplayBody(\Twig_Compiler $compiler)
    {
        parent::compileDisplayBody($compiler);

        $compiler
            ->write("\$deferred = \$this->env->getExtension('deferred');\n")
            ->write("\$template = \$this;\n")
            ->raw("\n")
            ->write("while (\$template) {\n")
            ->indent()
            ->write("\$deferred->resolve(\$template);\n")
            ->write("\$template = \$template->parent;\n")
            ->outdent()
            ->write("}\n")
        ;
    }
}
