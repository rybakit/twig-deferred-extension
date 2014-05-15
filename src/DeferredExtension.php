<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $blocks = array();

    /**
     * @var bool
     */
    private $isResolving = false;

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(new DeferredTokenParser());
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return array(new DeferredNodeVisitor());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'deferred';
    }

    public function defer(\Twig_Template $template, $blockName, array $args)
    {
        if ($this->isResolving) {
            call_user_func_array(array($template, $blockName), $args);

            return;
        }

        $templateName = $template->getTemplateName();
        $this->blocks[$templateName][] = array($blockName, $args);
        ob_start();
    }

    public function resolve(\Twig_Template $template)
    {
        $templateName = $template->getTemplateName();
        if (empty($this->blocks[$templateName])) {
            return;
        }

        $this->isResolving = true;

        while ($block = array_pop($this->blocks[$templateName])) {
            $buffer = ob_get_clean();
            call_user_func_array(array($template, $block[0]), $block[1]);
            echo $buffer;
        }

        $this->isResolving = false;
    }
}
