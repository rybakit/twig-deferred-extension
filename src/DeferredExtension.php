<?php

/**
 * This file is part of the rybakit/twig-deferred-extension package.
 *
 * (c) Eugene Leonovich <gen.work@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Twig\DeferredExtension;

use Twig\Extension\AbstractExtension;
use Twig\Template;

final class DeferredExtension extends AbstractExtension
{
    private $first = null;
    private $blocks = [];

    public function getTokenParsers() : array
    {
        return [new DeferredTokenParser()];
    }

    public function getNodeVisitors() : array
    {
        return [new DeferredNodeVisitor()];
    }

    public function markIfFirst(Template $template): void
    {
        if ($this->first === null) {
            $this->first = $template;
        }
    }

    public function defer(Template $template, string $blockName) : void
    {
        $this->blocks[] = [$template, $blockName];
        $key = \array_key_last($this->blocks);
        \ob_start(function (string $buffer) use ($key) {
            unset($this->blocks[$key]);
            return $buffer;
        });
    }

    public function resolve(Template $template, array $context, array $blocks) : void
    {
        if ($this->first !== $template || empty($this->blocks)) {
            return;
        }

        // We don't use array_pop() here because it doesn't preserve keys, and we need it for the buffer callback
        while (!empty($this->blocks)) {
            $key = \array_key_last($this->blocks);
            [$blockTemplate, $blockName] = $this->blocks[$key];
            unset($this->blocks[$key]);

            $buffer = \ob_get_clean();

            $blocks[$blockName] = [$blockTemplate, 'block_'.$blockName.'_deferred'];
            $template->displayBlock($blockName, $context, $blocks);

            echo $buffer;
        }
    }
}
