<?php

namespace Phive\Twig\Extensions\Deferred;

use Twig\Extension\AbstractExtension;
use Twig\Template;

class DeferredExtension extends AbstractExtension
{
    private $blocks = [];

    public function getTokenParsers()
    {
        return [new DeferredTokenParser()];
    }

    public function getNodeVisitors()
    {
        return [new DeferredNodeVisitor()];
    }

    public function defer(Template $template, $blockName)
    {
        $templateName = $template->getTemplateName();
        $this->blocks[$templateName][] = $blockName;
        ob_start();
    }

    public function resolve(Template $template, array $context, array $blocks)
    {
        $templateName = $template->getTemplateName();
        if (empty($this->blocks[$templateName])) {
            return;
        }

        while ($blockName = array_pop($this->blocks[$templateName])) {
            $buffer = ob_get_clean();

            $blocks[$blockName] = [$template, 'block_'.$blockName.'_deferred'];
            $template->displayBlock($blockName, $context, $blocks);

            echo $buffer;
        }

        if ($parent = $template->getParent($context)) {
            $this->resolve($parent, $context, $blocks);
        }
    }
}
