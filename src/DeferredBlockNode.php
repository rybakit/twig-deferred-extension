<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredBlockNode extends \Twig_Node_Block
{
    public function compile(\Twig_Compiler $compiler)
    {
        $name = $this->getAttribute('name');

        $compiler
            ->write("public function block_$name(\$context, array \$blocks = [])\n", "{\n")
            ->indent()
            ->write("\$this->env->getExtension('".DeferredExtension::class."')->defer(\$this, '$name');\n")
            ->outdent()
            ->write("}\n\n")
        ;

        $compiler
            ->addDebugInfo($this)
            ->write("public function block_{$name}_deferred(\$context, array \$blocks = [])\n", "{\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->write("\$this->env->getExtension('".DeferredExtension::class."')->resolve(\$this, \$context, \$blocks);\n")
            ->outdent()
            ->write("}\n\n")
        ;
    }
}
