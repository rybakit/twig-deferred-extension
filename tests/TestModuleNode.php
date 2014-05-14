<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

class TestModuleNode extends \Twig_Node_Module
{
    public function __construct(\Twig_Node_Module $node)
    {
        parent::__construct($node->getNode('body'), $node->getNode('parent'), $node->getNode('blocks'), $node->getNode('macros'), $node->getNode('traits'), $node->getAttribute('embedded_templates'), $node->getAttribute('filename'));
    }

    protected function compileDisplayBody(\Twig_Compiler $compiler)
    {
        parent::compileDisplayBody($compiler);

        $compiler
            ->write("if (isset(\$context['body_extra'])) {\n")
            ->indent()
            ->write("echo \$context['body_extra'];\n")
            ->outdent()
            ->write("}\n")
        ;
    }
}
