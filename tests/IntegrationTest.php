<?php

namespace Phive\Twig\Extensions\Tests\Deferred;

use Phive\Twig\Extensions\Deferred\DeferredExtension;

class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    public function getExtensions()
    {
        return array(
            new DeferredExtension(),
            new TestExtension(),
        );
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }
}
