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

namespace Twig\DeferredExtension\Tests;

use Twig\Environment;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;

final class TestNodeVisitor implements NodeVisitorInterface
{
    public function enterNode(Node $node, Environment $env) : Node
    {
        return $node;
    }

    public function leaveNode(Node $node, Environment $env) : ?Node
    {
        if ($node instanceof ModuleNode) {
            $node->setNode('display_end', new Node([new TestNode(), $node->getNode('display_end')]));
        }

        return $node;
    }

    public function getPriority() : int
    {
        return 0;
    }
}
