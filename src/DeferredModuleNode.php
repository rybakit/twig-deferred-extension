<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredModuleNode extends \Twig_Node_Module
{
    public function __construct(\Twig_Node_Module $node)
    {
        parent::__construct($node->getNode('body'), $node->getNode('parent'), $node->getNode('blocks'), $node->getNode('macros'), $node->getNode('traits'), $node->getAttribute('embedded_templates'), $node->getAttribute('filename'));

        $this->setAttribute('index', $node->getAttribute('index'));
    }

    protected function compileDisplayBody(\Twig_Compiler $compiler)
    {
        parent::compileDisplayBody($compiler);

        $compiler
            ->write("\$this->env->getExtension('deferred')->resolve(\$this, \$context, \$blocks);\n")
        ;
    }
}
