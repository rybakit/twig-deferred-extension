<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredNode extends \Twig_Node_Block
{
    public function compile(\Twig_Compiler $compiler)
    {
        $name = $this->getAttribute('name');

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("public function block_%s(\$context, array \$blocks = array())\n", $name), "{\n")
            ->indent()
            ->write(sprintf("\$this->env->getExtension('deferred')->defer(\$this, 'block_do_%s', array(\$context, \$blocks));\n", $name))
            ->outdent()
            ->write("}\n\n")
        ;

        $this->setAttribute('name', 'do_'.$name);
        parent::compile($compiler);
        $this->setAttribute('name', $name);
    }
}
