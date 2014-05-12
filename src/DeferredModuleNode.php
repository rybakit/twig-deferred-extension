<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredModuleNode extends \Twig_Node_Module
{
    public function __construct(\Twig_Node_Module $node)
    {
        parent::__construct($node->getNode('body'), $node->getNode('parent'), $node->getNode('blocks'), $node->getNode('macros'), $node->getNode('traits'), $node->getAttribute('embedded_templates'), $node->getAttribute('filename'));
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
            ->write("\$template = \$template->getParent(\$context);")
            ->outdent()
            ->write("}\n")
        ;
    }
}
