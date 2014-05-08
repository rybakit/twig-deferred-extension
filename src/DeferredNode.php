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
            ->write("\$this->env->getExtension('deferred')->defer(\$this, 'block_do_".$name."', array(\$context, \$blocks));\n")
            ->write("ob_start();\n")
            ->outdent()
            ->write("}\n\n")
        ;

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("public function block_do_%s(\$context, array \$blocks = array())\n", $name), "{\n")
            ->indent()
            ->write("\$content = ob_get_clean();\n")
            ->subcompile($this->getNode('body'))
            ->write("echo \$content;\n")
            ->outdent()
            ->write("}\n\n")
        ;
    }
}
