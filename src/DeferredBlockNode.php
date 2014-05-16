<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredBlockNode extends \Twig_Node_Block
{
    public function __construct(\Twig_Node_Block $node)
    {
        parent::__construct($node->getAttribute('name'), $node->getNode('body'), $node->getLine(), $node->getNodeTag());
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $name = $this->getAttribute('name');

        $compiler
            ->write("public function block_$name(\$context, array \$blocks = array())\n", "{\n")
            ->indent()
            ->write("\$this->env->getExtension('deferred')->defer(\$this, 'block_do_$name', array(\$context, \$blocks));\n")
            ->outdent()
            ->write("}\n\n")
        ;

        $this->setAttribute('name', 'do_'.$name);
        parent::compile($compiler);
        $this->setAttribute('name', $name);
    }
}
