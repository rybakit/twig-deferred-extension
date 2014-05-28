<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $blocks = array();

    /**
     * @var array
     */
    private $scopes = array();

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

    public function defer(\Twig_Template $template, $blockName)
    {
        if ($this->scopes) {
            list($context, $blocks) = end($this->scopes);
            $template->displayBlock($blockName, $context, $blocks);

            return;
        }

        $templateName = $template->getTemplateName();
        $this->blocks[$templateName][] = $blockName;
        ob_start();
    }

    public function resolve(\Twig_Template $template, array $context, array $blocks)
    {
        $templateName = $template->getTemplateName();
        if (empty($this->blocks[$templateName])) {
            return;
        }

        $this->scopes[] = array($context, $blocks);

        while ($blockName = array_pop($this->blocks[$templateName])) {
            $buffer = ob_get_clean();
            $template->displayBlock($blockName, $context, $blocks);
            echo $buffer;
        }

        array_pop($this->scopes);
    }
}
