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

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

final class TestExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals() : array
    {
        return ['data' => new \ArrayObject()];
    }

    public function getNodeVisitors() : array
    {
        return [new TestNodeVisitor()];
    }
}
