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
    private $resolvingBlocks = array();

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

    public function defer(\Twig_Template $template, $blockName, array $context, array $blocks)
    {
        if ($this->isResolving) {
            $this->resolvingBlocks[] = array($template, $blockName);

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

        $this->isResolving = true;

        while ($blockName = array_pop($this->blocks[$templateName])) {
            $buffer = ob_get_clean();
            $this->resolveBlock($template, $blockName, $context, $blocks);

            while ($block = array_pop($this->resolvingBlocks)) {
                $this->resolveBlock($block[0], $block[1], $context, $blocks);
            }

            echo $buffer;
        }

        $this->isResolving = false;
    }

    private function resolveBlock(\Twig_Template $template, $blockName, array $context, array $blocks)
    {
        $blocks[$blockName] = array($template, 'block_'.$blockName.'_deferred');
        $template->displayBlock($blockName, $context, $blocks);
    }
}
