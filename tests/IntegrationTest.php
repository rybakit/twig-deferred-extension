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
use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Extension\StringLoaderExtension;
use Twig\Loader\ArrayLoader;
use Twig\Test\IntegrationTestCase;
use Twig\TwigFunction;

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

    public function testDeferredBlocksAreClearedOnRenderingError() : void
    {
        $loader = new ArrayLoader([
            'base' => '
                {%- block foo deferred -%}*{%- endblock -%}
                {{- block("foo") -}}
                {{- block("foo") -}}
                {% block content "base content" -%}
            ',
            'page_that_triggers_error' => '
                {% extends "base" %}
                {% block content %}{{ error() }}{% endblock %}
            ',
            'fallback_page' => '
                {% extends "base" %}
                {% block content "fallback content" %}
            ',
        ]);

        $twig = new Environment($loader, [
            'cache' => false,
            'strict_variables' => true,
        ]);

        $twig->addExtension(new DeferredExtension());
        $twig->addFunction(new TwigFunction('error', static function () {
            throw new \RuntimeException('Oops');
        }));

        try {
            $result = $twig->render('page_that_triggers_error');
        } catch (RuntimeError $e) {
            self::assertSame(
                'An exception has been thrown during the rendering of a template ("Oops").',
                $e->getRawMessage()
            );

            $result = $twig->render('fallback_page');
        }

        self::assertSame('***fallback content', $result);
    }
}
