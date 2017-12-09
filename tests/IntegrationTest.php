<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

use Phive\Twig\Extensions\Deferred\DeferredExtension;

class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new DeferredExtension(),
            new TestExtension(),
            new \Twig_Extension_StringLoader(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures';
    }
}
