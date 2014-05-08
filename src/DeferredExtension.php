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
    private $callbacks = array();

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

    public function addCallback(\Twig_Template $template, $blockName, array $args)
    {
        $name = $template->getTemplateName();

        if (!isset($this->callbacks[$name])) {
            $this->callbacks[$name] = array();
        }

        $this->callbacks[$name][] = array($blockName, $args);
    }

    public function resolve(\Twig_Template $template)
    {
        $name = $template->getTemplateName();

        if (empty($this->callbacks[$name])) {
            return;
        }

        while ($callback = array_pop($this->callbacks[$name])) {
            call_user_func_array([$template, $callback[0]], $callback[1]);
        }
    }
}
