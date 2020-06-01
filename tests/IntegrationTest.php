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

use Twig\DeferredExtension\DeferredExtension;
use Twig\Extension\StringLoaderExtension;
use Twig\Test\IntegrationTestCase;

final class IntegrationTest extends IntegrationTestCase
{
    public function getExtensions() : array
    {
        return [
            new DeferredExtension(),
            new TestExtension(),
            new StringLoaderExtension(),
        ];
    }

    public function getFixturesDir() : string
    {
        return __DIR__.'/Fixtures';
    }
}
