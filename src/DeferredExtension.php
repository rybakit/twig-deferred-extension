<?php

namespace Phive\Twig\Extensions\Deferred;

class DeferredExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var array
     */
    private $blocks = array();

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

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
        $templateName = $template->getTemplateName();

        if (!isset($this->blocks[$templateName])) {
            $this->blocks[$templateName] = array();
        }

        $this->blocks[$templateName][] = array($blockName, $args);
    }

    public function resolve(\Twig_Template $template)
    {
        $templateName = $template->getTemplateName();

        if (empty($this->blocks[$templateName])) {
            return;
        }

        while ($block = array_pop($this->blocks[$templateName])) {
            call_user_func_array([$template, $block[0]], $block[1]);
        }
    }
}
