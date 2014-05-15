<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredNode extends \Twig_Node_Block
{
    public function compile(\Twig_Compiler $compiler)
    {
        $name = $this->getAttribute('name');

        $compiler
            ->addDebugInfo($this)
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
